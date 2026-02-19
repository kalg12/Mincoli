<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; background-color: #f0f0f0; padding: 20px; }
        .ticket { width: 80mm; background-color: white; margin: 0 auto; padding: 10mm; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .logo { max-width: 50mm; height: auto; margin-bottom: 5px; }
        .store-name { font-size: 14px; font-weight: bold; }
        .transaction-number { font-size: 10px; text-align: center; margin: 5px 0; font-weight: bold; }
        .section { margin: 10px 0; font-size: 9px; }
        .section-title { font-weight: bold; border-bottom: 1px dashed #000; margin-bottom: 5px; text-transform: uppercase; }
        .customer-info { line-height: 1.4; }
        .customer-info p { margin: 2px 0; }
        .items-table { width: 100%; border-collapse: collapse; font-size: 8px; margin: 10px 0; }
        .items-table th { border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 3px 0; text-align: left; font-weight: bold; }
        .items-table td { padding: 3px 0; border-bottom: 1px dotted #ccc; }
        .item-qty { text-align: center; width: 15%; }
        .item-price { text-align: right; width: 20%; }
        .totals { border-top: 2px solid #000; border-bottom: 2px solid #000; margin: 10px 0; padding: 5px 0; font-size: 10px; }
        .total-row { display: flex; justify-content: space-between; margin: 3px 0; }
        .total-row.final { font-weight: bold; font-size: 11px; }
        .footer { text-align: center; font-size: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px solid #000; }
        .status-badge { display: inline-block; background-color: #f0f0f0; padding: 2px 4px; border-radius: 2px; font-size: 8px; font-weight: bold; }
        @media print {
            body { background-color: white; padding: 0; }
            .ticket { width: 100%; box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            @if(file_exists(public_path('apple-touch-icon.png')))
                <img src="{{ asset('apple-touch-icon.png') }}" alt="Logo" class="logo" style="width: 50px; height: 50px;">
            @endif
            <div class="store-name">MINCOLI</div>
            <div style="font-size: 9px;">Tienda Online</div>
        </div>

        <div class="transaction-number">
            ORDEN #{{ $order->order_number }}
        </div>
        <div style="text-align: center; font-size: 9px; margin-bottom: 10px;">
            {{ $order->created_at->format('d/m/Y H:i') }}
        </div>

        @if($order->customer_name)
            <div class="section">
                <div class="section-title">Cliente</div>
                <div class="customer-info">
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    @if($order->customer_phone) <p>Tel: {{ $order->customer_phone }}</p> @endif
                </div>
            </div>
        @endif

        <div class="section">
            <div class="section-title">Productos</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Descripción</th>
                        <th class="item-qty">Cant</th>
                        <th class="item-price">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div>{{ $item->product ? $item->product->name : 'Producto Eliminado' }}</div>
                                @if($item->variant)
                                    <div style="font-size: 7px; color: #666;">{{ $item->variant->name }}</div>
                                @endif
                            </td>
                            <td class="item-qty">{{ $item->quantity }}</td>
                            <td class="item-price">{{ $showIva ? '$' . number_format($item->total, 2) : '$' . number_format($item->unit_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($showIva)
                <div class="total-row">
                    <span>IVA:</span>
                    <span>${{ number_format($order->iva_total, 2) }}</span>
                </div>
            @endif
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
            @if($order->total_paid > 0)
                <div class="total-row" style="color: #22c55e; font-weight: bold;">
                    <span>Pagado:</span>
                    <span>${{ number_format($order->total_paid, 2) }}</span>
                </div>
            @endif
            @if($order->remaining > 0)
                <div class="total-row" style="color: #ef4444; font-weight: bold;">
                    <span>Restante:</span>
                    <span>${{ number_format($order->remaining, 2) }}</span>
                </div>
            @endif
        </div>

        <div style="text-align: center; margin: 10px 0; font-size: 9px;">
            @php
                $statusLabels = [
                    'paid' => 'PAGADO',
                    'pending' => 'PENDIENTE',
                    'partially_paid' => 'PAGO PARCIAL',
                    'cancelled' => 'CANCELADO',
                    'refunded' => 'REEMBOLSADO'
                ];
            @endphp
            <div class="status-badge">Estado: {{ $statusLabels[$order->status] ?? strtoupper($order->status) }}</div>
            @if($order->remaining > 0)
                <div style="margin-top: 5px; color: #ef4444; font-weight: bold; font-size: 8px;">
                    PAGO PARCIAL - Saldo pendiente: ${{ number_format($order->remaining, 2) }}
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Gracias por tu compra</p>
            <p style="margin-top: 5px; font-size: 7px;">mincoli.com</p>
        </div>
    </div>
    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
