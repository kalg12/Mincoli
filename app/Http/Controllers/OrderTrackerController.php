<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderTrackerController extends Controller
{
    public function index()
    {
        return view('pages.tracker');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|exists:orders,order_number',
        ], [
            'order_number.exists' => 'No encontramos un pedido con ese número. Verifícalo e intenta de nuevo.',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->with(['items.product', 'statusHistory'])
            ->firstOrFail();

        return view('pages.tracker', compact('order'));
    }
}
