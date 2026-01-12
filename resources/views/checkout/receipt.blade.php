<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pedido #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8" onload="window.print()">

    <div class="max-w-xl mx-auto bg-white p-8 shadow-lg print:shadow-none">
        <div class="text-center mb-8 border-b pb-8">
            <h1 class="text-3xl font-bold uppercase tracking-widest text-gray-900">Comprobante</h1>
            <p class="text-gray-500 mt-2">Pedido #{{ $order->order_number }}</p>
        </div>

        <div class="mb-8">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <h3 class="font-bold text-gray-900 uppercase mb-1">Cliente</h3>
                    <p>{{ $order->customer_name }}</p>
                    <p>{{ $order->customer_email }}</p>
                    <p>{{ $order->customer_phone }}</p>
                </div>
                <div class="text-right">
                    <h3 class="font-bold text-gray-900 uppercase mb-1">Fecha</h3>
                    <p>{{ $order->created_at->format('d/m/Y') }}</p>
                    <p>{{ $order->created_at->format('H:i') }}</p>
                    <h3 class="font-bold text-gray-900 uppercase mt-4 mb-1">Estado</h3>
                    <p class="uppercase font-semibold">{{ __($order->status) }}</p>
                </div>
            </div>
        </div>

        <table class="w-full mb-8 text-sm">
            <thead>
                <tr class="border-b-2 border-gray-800 text-left">
                    <th class="py-2">Producto</th>
                    <th class="py-2 text-right">Cant</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                <tr>
                    <td class="py-3">
                        <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                        <div class="text-gray-500 text-xs">{{ $item->variant->name ?? '' }}</div>
                    </td>
                    <td class="py-3 text-right">{{ $item->quantity }}</td>
                    <td class="py-3 text-right">${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-gray-800">
                <tr>
                    <td colspan="2" class="py-2 text-right font-bold">Total</td>
                    <td class="py-2 text-right font-bold text-xl">${{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if($order->status == 'pending' && $order->payments->first())
        <div class="bg-gray-50 border border-gray-200 p-4 rounded text-sm mb-8">
            <h3 class="font-bold mb-2">Instrucciones de Pago ({{ $order->payments->first()->method->name }})</h3>
            <p class="whitespace-pre-line text-gray-700">{{ $order->payments->first()->method->instructions }}</p>
        </div>
        @endif

        <div class="text-center text-xs text-gray-400 mt-12 pt-8 border-t">
            <p>Gracias por tu compra.</p>
            <p>{{ config('app.url') }}</p>
        </div>

        <div class="mt-8 text-center no-print">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 font-bold">
                Imprimir / Guardar como PDF
            </button>
            <a href="{{ route('checkout.success', $order->id) }}" class="block mt-4 text-blue-600 hover:underline">Volver</a>
        </div>
    </div>

</body>
</html>
