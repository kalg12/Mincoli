<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Abonos - {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; background-color: white !important; }
            .print-shadow-none { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-zinc-50 p-6 md:p-12">
    <div class="max-w-4xl mx-auto bg-white border border-zinc-200 p-8 md:p-16 print-shadow-none shadow-sm rounded-xl">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-zinc-900 pb-10">
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tighter text-zinc-900">Mincoli</h1>
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest mt-1">Tienda Online</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black uppercase text-zinc-900">Estado de Cuenta</h2>
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">Pedido #{{ $order->order_number }}</p>
                <p class="text-[10px] text-zinc-400 font-medium mt-1">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-12 mt-12 pb-12 border-b border-zinc-100">
            <div>
                <h3 class="text-[10px] font-black uppercase text-zinc-400 mb-4 tracking-widest">Información del Cliente</h3>
                <div class="space-y-1">
                    <p class="text-sm font-black uppercase text-zinc-900">{{ $order->customer_name }}</p>
                    <p class="text-xs font-bold text-zinc-500">{{ $order->customer_phone }}</p>
                    <p class="text-xs text-zinc-500">{{ $order->customer_email }}</p>
                </div>
            </div>
            <div class="text-right">
                <h3 class="text-[10px] font-black uppercase text-zinc-400 mb-4 tracking-widest">Resumen de Venta</h3>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-medium text-zinc-500">
                        <span>Total del Pedido:</span>
                        <span class="text-zinc-900 font-black">${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-medium text-zinc-500">
                        <span>Total Pagado:</span>
                        <span class="text-emerald-600 font-black">${{ number_format($order->total_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-black border-t border-zinc-50 pt-2 mt-2">
                        <span>SALDO PENDIENTE:</span>
                        <span class="text-red-500">${{ number_format($order->remaining, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="mt-12">
            <h3 class="text-[10px] font-black uppercase text-zinc-400 mb-6 tracking-widest">Detalle de Pagos / Abonos</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200">
                        <th class="py-4 text-[10px] font-black uppercase text-zinc-400 tracking-widest">Fecha</th>
                        <th class="py-4 text-[10px] font-black uppercase text-zinc-400 tracking-widest text-center">Método</th>
                        <th class="py-4 text-[10px] font-black uppercase text-zinc-400 tracking-widest">Referencia</th>
                        <th class="py-4 text-[10px] font-black uppercase text-zinc-400 tracking-widest text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->payments as $payment)
                    <tr class="border-b border-zinc-100">
                        <td class="py-4 text-xs font-bold text-zinc-600">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-4 text-xs font-bold text-zinc-600 text-center">{{ $payment->method->name }}</td>
                        <td class="py-4 text-[10px] font-medium text-zinc-400 italic">{{ $payment->reference ?? '-' }}</td>
                        <td class="py-4 text-right text-sm font-black text-zinc-900">${{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-24 pt-10 border-t border-zinc-100 text-center">
            <p class="text-[10px] font-black uppercase text-zinc-400 tracking-[0.3em]">Gracias por tu confianza</p>
            <p class="text-[9px] text-zinc-300 font-medium mt-2">Este documento es un comprobante interno de abonos registrados para la orden especificada.</p>
        </div>
    </div>

    <!-- Print Button (Hidden on Print) -->
    <div class="max-w-4xl mx-auto mt-8 flex justify-center gap-4 no-print">
        <button onclick="window.print()" class="bg-zinc-900 text-white px-8 py-3 rounded-full font-black uppercase text-xs hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-900/20">
            <i class="fas fa-print mr-2"></i> Imprimir / Guardar como PDF
        </button>
        <button onclick="window.close()" class="bg-zinc-200 text-zinc-600 px-8 py-3 rounded-full font-black uppercase text-xs hover:bg-zinc-300 transition-all">
            Cerrar Ventana
        </button>
    </div>
</body>
</html>
