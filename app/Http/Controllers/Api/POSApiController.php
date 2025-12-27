<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\POSTransaction;
use App\Models\POSTransactionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class POSApiController extends Controller
{
    /**
     * Buscar productos por SKU, Barcode o nombre
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('sku', 'like', "%$query%")
                    ->orWhere('barcode', 'like', "%$query%")
                    ->orWhere('name', 'like', "%$query%");
            })
            ->with(['variants', 'category'])
            ->select('id', 'name', 'sku', 'barcode', 'price', 'sale_price', 'iva_rate', 'stock', 'category_id')
            ->limit(15)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'price' => (float) $product->price,
                    'sale_price' => (float) ($product->sale_price ?? $product->price),
                    'iva_rate' => (float) $product->iva_rate,
                    'stock' => $product->stock,
                    'category' => $product->category->name ?? null,
                    'variants' => $product->variants->map(fn ($v) => [
                        'id' => $v->id,
                        'name' => $v->name,
                        'sku' => $v->sku,
                        'barcode' => $v->barcode,
                        'price' => (float) $v->price,
                        'stock' => $v->stock,
                    ]),
                ];
            });

        return response()->json($products);
    }

    /**
     * Obtener detalles de producto con variantes
     */
    public function getProduct(Product $product): JsonResponse
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'price' => (float) $product->price,
            'sale_price' => (float) ($product->sale_price ?? $product->price),
            'iva_rate' => (float) $product->iva_rate,
            'stock' => $product->stock,
            'category' => $product->category?->name,
            'variants' => $product->variants->map(fn ($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'sku' => $v->sku,
                'barcode' => $v->barcode,
                'price' => (float) $v->price,
                'stock' => $v->stock,
            ]),
        ]);
    }

    /**
     * Obtener transacción con items (para AJAX)
     */
    public function getTransaction(POSTransaction $transaction): JsonResponse
    {
        Gate::authorize('view', $transaction);

        return response()->json([
            'id' => $transaction->id,
            'transaction_number' => $transaction->transaction_number,
            'customer' => $transaction->customer ? [
                'id' => $transaction->customer->id,
                'name' => $transaction->customer->name,
                'phone' => $transaction->customer->phone,
                'email' => $transaction->customer->email,
            ] : null,
            'subtotal' => (float) $transaction->subtotal,
            'iva_total' => (float) $transaction->iva_total,
            'total' => (float) $transaction->total,
            'items' => $transaction->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'total' => (float) $item->total,
                'status' => $item->status,
            ]),
            'payments' => $transaction->payments->map(fn ($p) => [
                'id' => $p->id,
                'amount' => (float) $p->amount,
                'method' => $p->paymentMethod?->name,
                'reference' => $p->reference,
                'paid_at' => $p->paid_at,
            ]),
            'total_paid' => (float) $transaction->total_paid,
            'pending_amount' => (float) $transaction->pending_amount,
            'payment_status' => $transaction->payment_status,
        ]);
    }

    /**
     * Obtener items pendientes por enviar
     */
    public function getPendingItems(): JsonResponse
    {
        $items = POSTransactionItem::where('status', 'pending_shipment')
            ->with(['posTransaction.customer', 'product', 'variant'])
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();

        return response()->json($items->map(fn ($item) => [
            'id' => $item->id,
            'transaction_id' => $item->pos_transaction_id,
            'transaction_number' => $item->posTransaction->transaction_number,
            'customer_name' => $item->posTransaction->customer?->name ?? 'Sin cliente',
            'customer_phone' => $item->posTransaction->customer?->phone,
            'product_name' => $item->product_name,
            'product_sku' => $item->product_sku,
            'product_barcode' => $item->product_barcode,
            'quantity' => $item->quantity,
            'status' => $item->status,
            'reserved_at' => $item->created_at,
        ]));
    }
}
