<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío.');
        }

        // Re-calculate totals to ensure accuracy
        $subtotal = 0;
        $items = collect($cart)->map(function ($item) use (&$subtotal) {
            $product = Product::with(['images', 'variants'])->find($item['product_id']);
            if (!$product) return null;

            $unitPrice = $product->sale_price ?? $product->price;
            $variant = null;

            if (!empty($item['variant_id'])) {
                $variant = $product->variants->firstWhere('id', $item['variant_id']);
                if ($variant) {
                    $unitPrice = $variant->sale_price ?? ($variant->price ?? $unitPrice);
                }
            }

            $lineTotal = $unitPrice * $item['quantity'];
            $subtotal += $lineTotal;

            return (object)[
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $lineTotal,
                'image_url' => $variant?->images->first()?->url ?? ($product->images->first()?->url ?? '/images/placeholder.jpg'),
                'name' => $product->name . ($variant ? " ({$variant->name})" : '')
            ];
        })->filter();

        $showIva = \App\Models\SiteSetting::get('store', 'show_iva', true);
        $iva = $showIva ? ($subtotal * 0.16) : 0;
        $total = $subtotal + $iva;

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('checkout.index', compact('items', 'subtotal', 'iva', 'total', 'paymentMethods'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|min:10|confirmed', // expects customer_phone_confirmation field
            'customer_email' => 'required|email',
        ], [
             'customer_phone.confirmed' => 'Los números de teléfono no coinciden.',
             'customer_phone.min' => 'El teléfono debe tener al menos 10 dígitos.'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío.');
        }

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        try {
            DB::beginTransaction();

            // Create Order
            $order = new Order();
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->status = 'pending'; // or draft/pending
            $order->customer_id = auth()->id(); // If logged in
            $order->customer_name = $request->customer_name;
            $order->customer_email = $request->customer_email;
            $order->customer_phone = $request->customer_phone;

            
            // Calculate totals again for security
             $subtotal = 0;
             // Logic to calculate subtotal similar to index...
             // For brevity, assuming simple calculation loop:
             foreach ($cart as $itemData) {
                 $product = Product::with('variants')->find($itemData['product_id']);
                 $price = $product->sale_price ?? $product->price;
                 if(!empty($itemData['variant_id'])) {
                     $variant = $product->variants->firstWhere('id', $itemData['variant_id']);
                     if($variant) $price = $variant->sale_price ?? ($variant->price ?? $price);
                 }
                 $subtotal += $price * $itemData['quantity'];
             }

            $showIva = \App\Models\SiteSetting::get('store', 'show_iva', true);
            $iva = $showIva ? ($subtotal * 0.16) : 0;
            $total = $subtotal + $iva;

            $order->subtotal = $subtotal;
            $order->iva_total = $iva;
            $order->total = $total;
            $order->channel = 'web';
            $order->placed_at = now();
            // Set Reservation Timeout (e.g., 15 minutes)
            $order->expires_at = now()->addMinutes(15);
            $order->save();

            // Create Order Items and Reserve Stock Atomically
            foreach ($cart as $itemData) {
                $product = Product::find($itemData['product_id']);
                $price = $product->sale_price ?? $product->price;
                $variant = null;
                
                if(!empty($itemData['variant_id'])) {
                    $variant = ProductVariant::find($itemData['variant_id']);
                    if($variant) $price = $variant->sale_price ?? ($variant->price ?? $price);
                }

                $subtotalItem = $price * $itemData['quantity'];
                $ivaItem = $showIva ? ($subtotalItem * 0.16) : 0;
                $totalItem = $subtotalItem + $ivaItem;

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->variant_id = $itemData['variant_id'] ?? null;
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->unit_price = $price;
                $orderItem->iva_amount = $ivaItem;
                $orderItem->total = $totalItem;
                $orderItem->save();

                // ATOMIC RESERVATION (Decrement WHERE stock >= requested)
                if ($orderItem->variant_id) {
                    $affected = DB::table('product_variants')
                        ->where('id', $orderItem->variant_id)
                        ->where('stock', '>=', $orderItem->quantity)
                        ->decrement('stock', $orderItem->quantity);
                } else {
                    $affected = DB::table('products')
                        ->where('id', $orderItem->product_id)
                        ->where('stock', '>=', $orderItem->quantity)
                        ->decrement('stock', $orderItem->quantity);
                }

                if ($affected === 0) {
                    throw new \Exception("Vaya, parece que alguien se llevó el último " . $product->name . " mientras terminabas. Por favor, revisa tu carrito.");
                }
            }

            // Create Payment Record (Pending)
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->method_id = $paymentMethod->id;
            $payment->amount = $total;
            $payment->status = 'pending';
            $payment->save();

            // COMMIT TRANSACTION HERE to prevent Database Locking (SQLite) during external API calls
            DB::commit();

            // Clear Cart immediately to prevent duplicate orders if user refreshes
            session()->forget('cart');
            session()->put('last_order_id', $order->id);

            // Handle Payment Method Specifics
            if ($paymentMethod->code === 'mercadopago') {
                try {
                    $preferenceData = $this->createMercadoPagoPreference($order, $paymentMethod);
                    return view('checkout.mercadopago', $preferenceData);
                } catch (\Exception $e) {
                    Log::error('Mercado Pago Init Failed after Order Commit', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                    // Order is created but payment init failed. Redirect to success/receipt page with error.
                    return redirect()->route('checkout.success', $order)
                        ->with('error', 'El pedido fue creado pero hubo un error al conectar con Mercado Pago: ' . $e->getMessage() . '. Por favor intenta pagar nuevamente desde tu historial.');
                }

            } elseif ($paymentMethod->code === 'oxxo') {
                return redirect()->route('checkout.success', $order);

            } elseif ($paymentMethod->code === 'transfer') {
                return redirect()->route('checkout.success', $order);
            }

            // Default fallback
            return redirect()->route('checkout.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            // Use specific error message if it's from our logic, generic otherwise
            $errorMessage = $e->getMessage();
            return back()->with('error', 'Error al procesar el pedido: ' . $errorMessage);
        }
    }
    
    /**
     * Creates Mercado Pago preference and returns view data.
     * Throws exception on failure.
     */
    private function createMercadoPagoPreference($order, $paymentMethod)
    {
        $settings = $paymentMethod->settings;
        
        // Hybrid Credential Logic: DB Settings > .env Config
        $accessToken = !empty($settings['access_token']) ? $settings['access_token'] : config('services.mercadopago.access_token');
        $publicKey = !empty($settings['public_key']) ? $settings['public_key'] : config('services.mercadopago.public_key');
        // Use sandbox if configured in .env or if we are investigating issues
        $isSandbox = config('services.mercadopago.sandbox', true);

        if (empty($accessToken)) {
             throw new \Exception('Credenciales de Mercado Pago no configuradas (Access Token faltante).');
        }

        // Initialize SDK with specific config class
        \MercadoPago\MercadoPagoConfig::setAccessToken($accessToken);
        
        $client = new \MercadoPago\Client\Preference\PreferenceClient();
        
        $items = [];
        foreach ($order->items as $orderItem) {
            $items[] = [
                "id" => $orderItem->variant_id ? "VAR-" . $orderItem->variant_id : "PROD-" . $orderItem->product_id,
                "title" => $orderItem->product->name . ($orderItem->variant ? " ({$orderItem->variant->name})" : ""),
                "quantity" => (int)$orderItem->quantity,
                "unit_price" => (float)$orderItem->unit_price,
                "currency_id" => "MXN",
                "description" => Str::limit($orderItem->product->description ?? 'Producto Mincoli', 255)
            ];
        }

        if ($order->iva_total > 0) {
            $items[] = [
                "id" => "TAX-IVA",
                "title" => "IVA (16%)",
                "quantity" => 1,
                "unit_price" => (float)$order->iva_total,
                "currency_id" => "MXN"
            ];
        }
        
        $nameParts = explode(' ', $order->customer_name, 2);
        $name = $nameParts[0];
        $surname = $nameParts[1] ?? 'Guest'; // Ensure surname is not empty if possible

        $payer = [
            "name" => $name,
            "surname" => $surname,
            "email" => $order->customer_email,
             // Phone removed to prevent format validation errors in guest checkout
        ];
        
        try {
            // Note: secure_url forces https, which breaks on local http://mincoli.test
            // standard route() uses the current scheme (http/https) correctly.
            // Note: MP API requires HTTPS for auto_return back_urls.
            // We use secure_url() to force HTTPS. Ensure your local environment supports it (e.g., 'herd secure').
            $backUrls = [
                "success" => secure_url(route('checkout.success', $order, false)),
                "failure" => secure_url(route('checkout.failure', ['order_id' => $order->id], false)), 
                "pending" => secure_url(route('checkout.success', $order, false))
            ];

            $request = [
                "items" => $items,
                "payer" => $payer,
                "payment_methods" => [
                    "excluded_payment_methods" => [],
                    "installments" => 12
                ],
                "back_urls" => $backUrls,
                "auto_return" => "approved",
                "external_reference" => (string)$order->id,
                "statement_descriptor" => "MINCOLI SHOP",
                "metadata" => [
                    "order_number" => $order->order_number
                ]
            ];

            $preference = $client->create($request);

            if ($preference && $preference->id) {
                // Select correct point based on environment
                $initPoint = $isSandbox ? $preference->sandbox_init_point : $preference->init_point;
                
                Log::info('Mercado Pago Preference Created', [
                    'id' => $preference->id,
                    'sandbox' => $isSandbox,
                    'init_point' => $initPoint,
                    'order_id' => $order->id,
                    'back_urls' => $backUrls
                ]);

                return compact('order', 'preference', 'paymentMethod', 'publicKey', 'initPoint', 'isSandbox');
            } else {
                 throw new \Exception('No se pudo obtener el ID de preferencia de Mercado Pago (Respuesta vacía).');
            }

        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            $response = $e->getApiResponse();
            $content = $response ? json_encode($response->getContent()) : 'No content';
            Log::error('Mercado Pago API Error', [
                'status' => $e->getCode(),
                'content' => $content,
                'message' => $e->getMessage()
            ]);
            
            $friendlyMessage = 'Error de API Mercado Pago';
            if ($response && $contentArr = $response->getContent()) {
                if (isset($contentArr['message'])) $friendlyMessage .= ': ' . $contentArr['message'];
                if (isset($contentArr['cause']) && is_array($contentArr['cause'])) {
                    foreach($contentArr['cause'] as $cause) {
                        $friendlyMessage .= ' | ' . ($cause['description'] ?? $cause['code'] ?? '');
                    }
                }
            }
            throw new \Exception($friendlyMessage);

        } catch (\Exception $e) {
            Log::error('Mercado Pago General Exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw new \Exception('Error Interno Mercado Pago: ' . $e->getMessage());
        }
    }

    public function success(Order $order, Request $request)
    {
        // Security Check
        $allowed = false;
        if (auth()->check() && $order->customer_id === auth()->id()) $allowed = true;
        elseif (session()->has('last_order_id') && session('last_order_id') == $order->id) $allowed = true;

        if (!$allowed) abort(403, 'No tienes permiso para ver este pedido.');

        $paymentId = $request->get('payment_id') ?? $request->get('collection_id');
        if ($paymentId && $order->status === 'pending') {
            $this->finalizePayment($order, $paymentId, 'Redirect');
        }

        $paymentMethod = $order->payments->sortByDesc('created_at')->first()?->method;
        return view('checkout.success', compact('order', 'paymentMethod'));
    }

    public function webhook(Request $request)
    {
        $type = $request->get('type') ?? $request->get('topic');
        $id = $request->get('data_id') ?? $request->get('id');

        Log::info('Mercado Pago Webhook', ['type' => $type, 'id' => $id]);

        if ($type === 'payment') {
            try {
                $accessToken = config('services.mercadopago.access_token');
                MercadoPagoConfig::setAccessToken($accessToken);
                $client = new PaymentClient();
                $mpPayment = $client->get($id);

                if ($mpPayment && isset($mpPayment->external_reference)) {
                    $orderId = $mpPayment->external_reference;
                    $order = Order::find($orderId);
                    if ($order && $order->status === 'pending' && $mpPayment->status === 'approved') {
                        $this->finalizePayment($order, $id, 'Webhook');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Webhook Processing Error', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function finalizePayment(Order $order, $transactionId, $source)
    {
        return DB::transaction(function () use ($order, $transactionId, $source) {
            // LOCK for update to prevent race conditions
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            if ($order->status !== 'pending') {
                return false; // Already processed
            }

            // Update Order
            $order->status = 'paid';
            $order->expires_at = null; // Clear expiration
            $order->save();

            \App\Models\OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => 'pending',
                'to_status' => 'paid',
                'note' => "Pago confirmado vía {$source}. Ref: {$transactionId}",
            ]);

            // Update Payment Record
            $payment = $order->payments->sortByDesc('created_at')->first();
            if ($payment) {
                $payment->status = 'paid';
                $payment->paid_at = now();
                $payment->transaction_id = $transactionId;
                $payment->save();
            }

            // Record Inventory Movement (IDEMPOTENT - Unique constraint will protect us too)
            foreach ($order->items as $item) {
                try {
                    DB::table('inventory_movements')->insert([
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'type' => 'out',
                        'quantity' => $item->quantity,
                        'reason' => 'Venta confirmada #' . $order->order_number,
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                        'created_at' => now(),
                        'created_by' => null,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // If error 19 (unique constraint) just ignore, it means another process won
                    if ($e->getCode() != 23000 && !str_contains($e->getMessage(), '19')) throw $e;
                }
            }

            return true;
        });
    }

    public function downloadReceipt(Order $order)
    {
        // Security check
        $allowed = false;
        if (auth()->check()) $allowed = true; // Allow any logged in user (Admins/Customers)
        elseif (session()->has('last_order_id') && session('last_order_id') == $order->id) $allowed = true;

        if (!$allowed) abort(403);

        return view('checkout.receipt', compact('order'));
    }
    public function failure(Request $request)
    {
        $orderId = $request->get('order_id') ?? $request->get('external_reference');
        
        if (!$orderId) {
            return redirect()->route('cart')->with('error', 'No se pudo identificar el pedido cancelado.');
        }

        $order = Order::with('items')->find($orderId);

        // Security: Ensure order belongs to user or session
        $allowed = false;
        if (auth()->check() && $order && $order->customer_id === auth()->id()) $allowed = true;
        elseif (session()->has('last_order_id') && session('last_order_id') == $orderId) $allowed = true;

        if (!$allowed || !$order) {
            return redirect()->route('cart')->with('error', 'Pedido no encontrado o no autorizado.');
        }

        // Cancel Logic (Restore Stock because it was reserved at start)
        if ($order->status === 'pending') {
            DB::transaction(function () use ($order) {
                // Cancel Order
                $order->status = 'cancelled';
                $order->expires_at = null;
                $order->save();

                \App\Models\OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => 'pending',
                    'to_status' => 'cancelled',
                    'note' => 'Cancelado automáticamente por fallo/cancelación en pasarela (Restauración de stock y carrito)',
                ]);

                // Restore Stock (increment)
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        \App\Models\ProductVariant::where('id', $item->variant_id)->increment('stock', $item->quantity);
                    } else {
                        \App\Models\Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            });
        }

        // Restore Cart Logic (Simplified restoration)
        $cart = [];
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $cartKey = $item->variant_id ? $item->product_id . '-' . $item->variant_id : $item->product_id;
                $cart[$cartKey] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'name' => $product->name,
                    'price' => $item->unit_price,
                    'image' => $product->images->first()?->url
                ];
            }
        }
        session()->put('cart', $cart);

        return redirect()->route('checkout.index')
            ->withInput([
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'payment_method_id' => $order->payments()->first()?->method_id
            ])
            ->with('error', 'El proceso de pago fue cancelado o falló. Hemos restaurado tu carrito para que puedas intentar nuevamente.');
    }
}

