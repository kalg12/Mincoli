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

    public function addPayment(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment = new \App\Models\Payment();
        $payment->order_id = $order->id;
        $payment->method_id = $request->payment_method_id;
        $payment->amount = $request->amount;
        $payment->reference = $request->reference; // e.g., Auth Code or Manual Note
        $payment->status = 'paid'; // Manual payments are usually confirmed immediately
        $payment->paid_at = now();
        $payment->save();

        // Update Order Status based on Balance
        $totalPaid = $order->total_paid; // Uses the fresh calculation including the new payment?
        // Need to reload relations or trust the helper which queries DB?
        // Model attribute uses relationship query, so we should refresh or just query again.
        $totalPaid = $order->payments()->where('status', 'paid')->sum('amount');

        if ($totalPaid >= $order->total) {
            $order->status = 'paid';
        } elseif ($totalPaid > 0) {
            $order->status = 'partially_paid';
        }
        $order->save();

        return back()->with('success', 'Pago registrado correctamente.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('dashboard.orders.index')->with('success', 'Pedido eliminado correctamente.');
    }

    public function destroyPayment(Order $order, $paymentId)
    {
        $payment = $order->payments()->findOrFail($paymentId);
        $payment->delete();

        // Recalculate Order Status
        $totalPaid = $order->payments()->where('status', 'paid')->sum('amount');
        if ($totalPaid >= $order->total) {
            $order->status = 'paid';
        } elseif ($totalPaid > 0) {
            $order->status = 'partially_paid';
        } else {
            $order->status = 'pending'; // Revert to pending if no payments left
        }
        $order->save();

        return back()->with('success', 'Pago eliminado correctamente.');
    }
}
