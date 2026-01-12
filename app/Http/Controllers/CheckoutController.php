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
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;
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
            }

            // Create Payment Record (Pending)
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->method_id = $paymentMethod->id;
            $payment->amount = $total;
            $payment->status = 'pending';
            $payment->save();

            DB::commit();

            // Clear Cart
            session()->forget('cart');
            session()->put('last_order_id', $order->id);

            // Handle Payment Method Specifics
            if ($paymentMethod->code === 'mercadopago') {
                return $this->processMercadoPago($order, $paymentMethod);
            } elseif ($paymentMethod->code === 'oxxo') {
                return redirect()->route('checkout.success', $order);
            } elseif ($paymentMethod->code === 'transfer') {
                return redirect()->route('checkout.success', $order);
            }

            return redirect()->route('checkout.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->with('error', 'Ocurrió un error al procesar tu pedido (' . $e->getMessage() . '). Por favor intenta de nuevo.');
        }
    }
    


    private function processMercadoPago($order, $paymentMethod)
    {
        $settings = $paymentMethod->settings;
        if (empty($settings['access_token'])) {
            // Fallback or error if not configured
             return redirect()->route('checkout.success', $order)->with('warning', 'Mercado Pago no está configurado correctamente. Contacta al administrador.');
        }

        SDK::setAccessToken($settings['access_token']);

        $preference = new Preference();
        
        $items = [];
        foreach ($order->items as $orderItem) {
            $item = new Item();
            $item->title = $orderItem->product->name . ($orderItem->variant ? " ({$orderItem->variant->name})" : "");
            $item->quantity = $orderItem->quantity;
            $item->unit_price = $orderItem->unit_price;
            // Mercado Pago expects unit_price to be float
            $item->unit_price = (float)$orderItem->unit_price; 
            $items[] = $item;
        }

        // Add tax as an item or included? Usually unit price includes tax if simple. 
        // Or add a separate item for tax ? better to have inclusive prices for simplicity in this demo or add generic tax item.
        // If system adds tax on top:
        if ($order->iva_total > 0) {
            $taxItem = new Item();
            $taxItem->title = "IVA (16%)";
            $taxItem->quantity = 1;
            $taxItem->unit_price = (float)$order->iva_total;
            $items[] = $taxItem;
        }

        $preference->items = $items;
        
        $preference->back_urls = [
            "success" => route('checkout.success', $order),
            "failure" => route('checkout.index'), // Or failure page
            "pending" => route('checkout.success', $order)
        ];
        $preference->auto_return = "approved";

        $preference->save();

        if ($preference->id) {
             return view('checkout.mercadopago', compact('order', 'preference', 'paymentMethod'));
        } else {
             return redirect()->route('checkout.success', $order)->with('error', 'Error al conectar con Mercado Pago.');
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

