<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'payments.method'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
         $order = Order::with(['customer', 'items.product.images', 'payments.method', 'statusHistory'])->findOrFail($id);
         return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,paid,shipped,delivered,cancelled,refunded']);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        if ($oldStatus !== $request->status) {
            // Log history if table exists or just save
            // Assuming simple update for now, ideally record history
        }

        // Also update payment status if Order is marked as Paid
        if ($request->status === 'paid') {
            foreach($order->payments as $payment) {
                if ($payment->status !== 'paid') {
                    $payment->status = 'paid';
                    $payment->paid_at = now();
                    $payment->save();
                }
            }
        }

        return back()->with('success', 'Estado del pedido actualizado correctamente.');
    }
}
