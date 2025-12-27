<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $transaction->transaction_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .ticket {
            width: 80mm;
            background-color: white;
            margin: 0 auto;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 50mm;
            height: auto;
            margin-bottom: 5px;
        }

        .store-name {
            font-size: 14px;
            font-weight: bold;
        }

        .transaction-number {
            font-size: 10px;
            text-align: center;
            margin: 5px 0;
            font-weight: bold;
        }

        .section {
            margin: 10px 0;
            font-size: 9px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px dashed #000;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .customer-info {
            line-height: 1.4;
        }

        .customer-info p {
            margin: 2px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin: 10px 0;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
            padding: 3px 0;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-qty {
            text-align: center;
            width: 15%;
        }

        .item-price {
            text-align: right;
            width: 20%;
        }

        .totals {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin: 10px 0;
            padding: 5px 0;
            font-size: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .total-row.final {
            font-weight: bold;
            font-size: 11px;
        }

        .payments {
            font-size: 9px;
            margin: 10px 0;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px dotted #ccc;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        .barcode-section {
            text-align: center;
            margin: 10px 0;
        }

        .barcode {
            font-weight: bold;
            letter-spacing: 2px;
            font-size: 12px;
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .ticket {
                width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="header">
            @php
                $logoPath = public_path('apple-touch-icon.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ asset('apple-touch-icon.png') }}" alt="Logo" class="logo" style="width: 50px; height: 50px;">
            @else
                <div style="width: 50px; height: 50px; background-color: #e0e0e0; margin: 0 auto 5px; border-radius: 4px;"></div>
            @endif
            <div class="store-name">MINCOLI</div>
            <div style="font-size: 9px;">Tienda Online</div>
        </div>

        <!-- Número de Transacción -->
        <div class="transaction-number">
            {{ $transaction->transaction_number }}
        </div>
        <div style="text-align: center; font-size: 9px; margin-bottom: 10px;">
            {{ $transaction->reserved_at->format('d/m/Y H:i') }}
        </div>

        <!-- Info Cliente -->
        @if($transaction->customer)
            <div class="section">
                <div class="section-title">Cliente</div>
                <div class="customer-info">
                    <p><strong>{{ $transaction->customer->name }}</strong></p>
                    @if($transaction->customer->phone)
                        <p>Tel: {{ $transaction->customer->phone }}</p>
                    @endif
                    @if($transaction->customer->email)
                        <p>{{ $transaction->customer->email }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Items -->
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
                    @foreach($transaction->items as $item)
                        <tr>
                            <td>
                                <div>{{ $item->product_name }}</div>
                                <div style="font-size: 7px; color: #666;">{{ $item->product_sku }}</div>
                            </td>
                            <td class="item-qty">{{ $item->quantity }}</td>
                            <td class="item-price">{{ $showIva ? currency($item->total) : currency($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>{{ currency($transaction->subtotal) }}</span>
            </div>
            @if($showIva)
                <div class="total-row">
                    <span>IVA:</span>
                    <span>{{ currency($transaction->iva_total) }}</span>
                </div>
            @endif
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>{{ currency($transaction->total) }}</span>
            </div>
        </div>

        <!-- Pagos -->
        @if($transaction->payments->count())
            <div class="section">
                <div class="section-title">Pagos Recibidos</div>
                <div class="payments">
                    @foreach($transaction->payments as $payment)
                        <div class="payment-item">
                            <span>{{ $payment->paymentMethod?->name ?? 'Pago' }}</span>
                            <span>{{ currency($payment->amount) }}</span>
                        </div>
                    @endforeach
                    <div class="payment-item" style="border-bottom: 1px solid #000; margin-top: 3px;">
                        <strong>Pagado Total:</strong>
                        <strong>{{ currency($transaction->total_paid) }}</strong>
                    </div>
                    @if($transaction->pending_amount > 0)
                        <div class="payment-item" style="border-bottom: none;">
                            <strong>Pendiente:</strong>
                            <strong>{{ currency($transaction->pending_amount) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Estado -->
        <div style="text-align: center; margin: 10px 0; font-size: 9px;">
            <div class="status-badge">Estado: {{ strtoupper(str_replace('_', ' ', $transaction->status)) }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por tu compra</p>
            <p style="margin-top: 5px; font-size: 7px;">Apartado generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto-print cuando se abre
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
