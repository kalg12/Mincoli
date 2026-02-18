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
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            /* Evitar líneas verticales en impresión */
            table {
                border-collapse: collapse;
            }
            td, th {
                border: none !important;
            }
            /* Mejorar visibilidad del footer en impresión */
            .footer-print {
                color: #6b7280 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-8" onload="window.print()">

     <div class="max-w-xl mx-auto bg-white p-8 shadow-lg print:shadow-none">
         <!-- Logo y Header -->
         <div class="text-center mb-8 border-b-2 border-pink-500 pb-6">
             <div class="mb-4">
                 <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="w-32 h-auto mx-auto">
             </div>
             <h1 class="text-3xl font-bold uppercase tracking-widest bg-gradient-to-r from-pink-600 to-pink-500 bg-clip-text text-transparent">Comprobante</h1>
             <p class="text-gray-600 mt-2">Pedido #{{ $order->order_number }}</p>
         </div>

         <div class="mb-8">
             <div class="grid grid-cols-2 gap-4 text-sm">
                  <div>
                      <h3 class="font-bold text-pink-600 uppercase mb-2">Cliente</h3>
                      <p class="text-gray-900 font-medium">{{ $order->customer_name }}</p>
                      <p class="text-gray-800">{{ $order->customer_email }}</p>
                      <p class="text-gray-800">{{ $order->customer_phone }}</p>
                  </div>
                 <div class="text-right">
                     <h3 class="font-bold text-pink-600 uppercase mb-2">Fecha</h3>
                     <p class="text-gray-700">{{ $order->created_at->format('d/m/Y') }}</p>
                     <p class="text-gray-700">{{ $order->created_at->format('H:i') }}</p>
                     <h3 class="font-bold text-pink-600 uppercase mt-4 mb-2">Estado</h3>
                     @php
                         $statusLabels = [
                             'paid' => 'PAGADO',
                             'pending' => 'PENDIENTE',
                             'partially_paid' => 'PAGO PARCIAL',
                             'cancelled' => 'CANCELADO',
                             'refunded' => 'REEMBOLSADO',
                             'shipped' => 'ENVIADO',
                             'delivered' => 'ENTREGADO'
                         ];
                         $statusText = $statusLabels[$order->status] ?? strtoupper($order->status);
                     @endphp
                     <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-full
                         {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                         {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                         {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                         {{ $statusText }}
                     </span>
                 </div>
             </div>
         </div>

          <table class="w-full mb-8 text-sm border-collapse">
              <thead>
                  <tr class="border-b-2 border-pink-500 text-left">
                      <th class="py-3 font-bold text-pink-600">Producto</th>
                      <th class="py-3 text-right font-bold text-pink-600">Cant</th>
                      <th class="py-3 text-right font-bold text-pink-600">Total</th>
                  </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                  @foreach($order->items as $item)
                  <tr>
                      <td class="py-3">
                          <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                          @if($item->variant)
                          <div class="text-gray-500 text-xs">{{ $item->variant->name }}</div>
                          @endif
                      </td>
                      <td class="py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                      <td class="py-3 text-right font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                  </tr>
                  @endforeach
              </tbody>
              <tfoot class="border-t-2 border-pink-500">
                  <tr>
                      <td colspan="2" class="py-4 text-right font-bold text-pink-600 text-lg">Total</td>
                      <td class="py-4 text-right font-bold text-2xl bg-gradient-to-r from-pink-600 to-pink-500 bg-clip-text text-transparent">${{ number_format($order->total, 2) }}</td>
                  </tr>
              </tfoot>
          </table>

         @if($order->status == 'pending' && $order->payments->first())
         <div class="border border-gray-300 p-4 rounded text-sm mb-6">
             <h3 class="font-bold text-gray-900 mb-2">Instrucciones de Pago ({{ $order->payments->first()->method->name }})</h3>
             <p class="whitespace-pre-line text-gray-700 leading-relaxed">{{ $order->payments->first()->method->instructions }}</p>
             <p class="mt-3 text-gray-700">Este tipo de pago se verificara y puede comprobarse en 24 hrs.</p>
         </div>
         @endif

         @php
             $mainPayment = $order->payments->first();
         @endphp

         @if($mainPayment && ($mainPayment->card_number || $mainPayment->transfer_number || $mainPayment->capture_line))
         <div class="border border-gray-300 p-4 rounded text-sm mb-8">
             <h3 class="font-bold text-gray-900 mb-2">
                 @if($mainPayment->card_number)
                     Datos de la Tarjeta
                 @else
                     Detalles de la Transferencia Bancaria
                 @endif
             </h3>
             <div class="space-y-2 text-gray-800">
                 @if($mainPayment->card_number)
                 <div class="flex justify-between">
                     <span>Número:</span>
                     <span class="font-mono font-bold">{{ $mainPayment->card_number }}</span>
                 </div>
                 <div class="flex justify-between">
                     <span>Tipo:</span>
                     <span class="font-semibold">{{ ucfirst($mainPayment->card_type === 'credit' ? 'Crédito' : 'Débito') }}</span>
                 </div>
                 @endif

                 @if($mainPayment->transfer_number)
                 <div class="flex justify-between">
                     <span>Núm. transferencia:</span>
                     <span class="font-mono font-bold">{{ $mainPayment->transfer_number }}</span>
                 </div>
                 @endif

                 @if($mainPayment->capture_line)
                 <div class="flex justify-between">
                     <span>Línea de captura:</span>
                     <span class="font-mono font-bold break-all">{{ $mainPayment->capture_line }}</span>
                 </div>
                 @endif

                 @if($mainPayment->method && $mainPayment->method->bank_name)
                 <div class="flex justify-between">
                     <span>Banco:</span>
                     <span>{{ $mainPayment->method->bank_name }}</span>
                 </div>
                 @endif
                 @if($mainPayment->card_holder_name)
                 <div class="flex justify-between">
                     <span>Titular:</span>
                     <span>{{ $mainPayment->card_holder_name }}</span>
                 </div>
                 @endif
             </div>
         </div>
         @endif

          <div class="text-center text-sm mt-12 pt-8 border-t border-pink-200 space-y-2 footer-print">
              <p class="font-medium text-pink-600 mb-2">Gracias por tu compra en Mincoli 💕</p>
              <p class="flex items-center justify-center gap-2 text-gray-700 font-medium">
                  <i class="fas fa-phone text-pink-500"></i>
                  Teléfono: 56-1170-1166
              </p>
              <p class="text-gray-700 font-medium">mincoli.com</p>
          </div>

         <div class="mt-8 text-center no-print space-y-3">
             <button onclick="window.print()" class="bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white px-8 py-3 rounded-lg shadow-lg hover:shadow-xl font-bold transition-all duration-300 transform hover:scale-105">
                 <i class="fas fa-print mr-2"></i>
                 Imprimir / Guardar como PDF
             </button>
             <div>
                 <a href="{{ route('checkout.success', $order->id) }}" class="text-pink-600 hover:text-pink-700 font-medium underline decoration-2 hover:decoration-pink-700 transition-colors">
                     Volver a la tienda
                 </a>
             </div>
         </div>
    </div>

</body>
</html>
