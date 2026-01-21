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
            $order->save();

            // Create Order Items
            foreach ($cart as $itemData) {
                $product = Product::with('variants')->find($itemData['product_id']);
                $price = $product->sale_price ?? $product->price;
                 if(!empty($itemData['variant_id'])) {
                     $variant = $product->variants->firstWhere('id', $itemData['variant_id']);
                     if($variant) $price = $variant->sale_price ?? ($variant->price ?? $price);
                 }


                $subtotalItem = $price * $itemData['quantity'];
                
                // Calculate IVA for item if needed, or assume included in price. 
                // Based on previous logic, IVA was calculated ON TOP of subtotal.
                // So unit_price is exclusive of tax? 
                // Let's assume unit_price is the base price.
                
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

                // Decrement Stock
                if ($orderItem->variant_id && $variant) {
                    $variant->decrement('stock', $itemData['quantity']);
                } else {
                    $product->decrement('stock', $itemData['quantity']);
                }

                // Record Movement
                DB::table('inventory_movements')->insert([
                    'product_id' => $product->id,
                    'variant_id' => $orderItem->variant_id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'reason' => 'Venta #' . $order->order_number,
                    'reference_type' => 'App\Models\Order',
                    'reference_id' => $order->id,
                    'created_at' => now(),
                    'created_by' => auth()->id() ?? null,
                ]);
            }

            // Create Payment Record (Pending)
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->method_id = $paymentMethod->id;
            $payment->amount = $total;
            $payment->status = 'pending';
            $payment->save();

            // Handle Payment Method Specifics
            if ($paymentMethod->code === 'mercadopago') {
                $preferenceData = $this->createMercadoPagoPreference($order, $paymentMethod);
                
                // Only commit if Preference creation was successful
                DB::commit();
                
                // Clear Cart
                session()->forget('cart');
                session()->put('last_order_id', $order->id);

                return view('checkout.mercadopago', $preferenceData);

            } elseif ($paymentMethod->code === 'oxxo') {
                DB::commit();
                session()->forget('cart');
                session()->put('last_order_id', $order->id);
                return redirect()->route('checkout.success', $order);

            } elseif ($paymentMethod->code === 'transfer') {
                DB::commit();
                session()->forget('cart');
                session()->put('last_order_id', $order->id);
                return redirect()->route('checkout.success', $order);
            }

            // Default fallback
            DB::commit();
            session()->forget('cart');
            session()->put('last_order_id', $order->id);
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

        // Simplify Payer to avoid validation errors with phone/address if not strictly needed
        // Preference API only requires email. Name/Surname are helpful but strict validation on phone can cause errors.
        
        $nameParts = explode(' ', $order->customer_name, 2);
        $name = $nameParts[0];
        $surname = $nameParts[1] ?? '';

        $payer = [
            "name" => $name,
            "surname" => $surname,
            "email" => $order->customer_email,
        ];

        // Only add phone if we are sure it's valid format, otherwise skip to prevent API error
        // Mercado Pago often validates area_code + number strictly.
        // For simplicity in this integration, we omit phone to avoid "Api error" on invalid format.
        
        try {
            $request = [
                "items" => $items,
                "payer" => $payer,
                "payment_methods" => [
                    "excluded_payment_methods" => [],
                    "installments" => 12
                ],
                "back_urls" => [
                    "success" => secure_url(route('checkout.success', $order, false)),
                    "failure" => secure_url(route('checkout.index', ['error' => 'payment_failed'], false)), 
                    "pending" => secure_url(route('checkout.success', $order, false))
                ],
                "auto_return" => "approved",
                "external_reference" => (string)$order->id,
                "statement_descriptor" => "MINCOLI SHOP",
                "metadata" => [
                    "order_number" => $order->order_number
                ]
            ];

            $preference = $client->create($request);

            if ($preference && $preference->id) {
                return compact('order', 'preference', 'paymentMethod', 'publicKey');
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
            
            // Try to extract a friendly message from content
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

    public function success(Order $order)
    {
        // Security Check: Ensure order belongs to current user or was just placed in this session
        $allowed = false;
        
        if (auth()->check() && $order->customer_id === auth()->id()) {
            $allowed = true;
        } elseif (session()->has('last_order_id') && session('last_order_id') == $order->id) {
            $allowed = true;
        }

        if (!$allowed) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        $paymentMethod = null;
        $payment = $order->payments->sortByDesc('created_at')->first();
        
        if ($payment) {
            $paymentMethod = $payment->method;
        }

        return view('checkout.success', compact('order', 'paymentMethod'));
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
}

