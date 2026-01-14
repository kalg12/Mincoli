<x-layouts.app :title="__('Venta Exitosa')">
    <div class="flex items-center justify-center min-vh-100 bg-zinc-950 p-6">
        <div class="w-full max-w-xl bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl">
            <!-- Animated Header -->
            <div class="relative py-12 flex flex-col items-center justify-center bg-gradient-to-br from-pink-600/20 to-zinc-900">
                <div class="success-checkmark mb-6">
                    <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="icon-circle"></div>
                        <div class="icon-fix"></div>
                    </div>
                </div>
                <h1 class="text-3xl font-black text-white uppercase tracking-tighter">¡Venta Exitosa!</h1>
                <p class="text-zinc-400 font-bold uppercase tracking-widest text-[10px] mt-2">Orden #{{ $order->order_number }}</p>
            </div>

            <!-- Order Summary -->
            <div class="p-10 space-y-8">
                <div class="grid grid-cols-2 gap-8 border-b border-zinc-800 pb-8">
                    <div>
                        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Cliente</p>
                        <p class="text-lg font-black text-white uppercase">{{ $order->customer_name ?? 'Público General' }}</p>
                        <p class="text-sm text-zinc-500 font-bold">{{ $order->customer_phone ?? 'Sin teléfono' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Pagado</p>
                        <p class="text-3xl font-black text-pink-500">${{ number_format($order->total, 0) }}</p>
                        <p class="text-xs text-zinc-500 font-bold uppercase mt-1">Método: Efectivo / POS</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('dashboard.pos.index') }}" class="w-full bg-white text-zinc-950 px-6 py-4 rounded-2xl font-black uppercase text-sm flex items-center justify-center gap-3 hover:bg-zinc-200 transition-all transform active:scale-95 shadow-xl shadow-white/5">
                        <i class="fas fa-plus-circle text-lg"></i> Nueva Venta
                    </a>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('dashboard.pos.ticket.print', $order->id) }}" target="_blank" class="bg-zinc-800 text-white px-6 py-4 rounded-2xl font-black uppercase text-xs flex items-center justify-center gap-2 hover:bg-zinc-700 transition-all border border-zinc-700 transform active:scale-95">
                            <i class="fas fa-print"></i> Ticket
                        </a>
                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="bg-zinc-800 text-white px-6 py-4 rounded-2xl font-black uppercase text-xs flex items-center justify-center gap-2 hover:bg-zinc-700 transition-all border border-zinc-700 transform active:scale-95">
                            <i class="fas fa-eye"></i> Detalle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .success-checkmark {
            width: 80px;
            height: 115px;
            margin: 0 auto;
        }
        .success-checkmark .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #db2777;
        }
        .success-checkmark .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }
        .success-checkmark .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }
        .success-checkmark .check-icon .icon-line {
            height: 5px;
            background-color: #db2777;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }
        .success-checkmark .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }
        .success-checkmark .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }
        .success-checkmark .check-icon .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid rgba(219, 39, 119, 0.2);
            position: absolute;
            box-sizing: content-box;
        }
        .success-checkmark .check-icon .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
        }

        @keyframes icon-line-tip {
            0% { width: 0; left: 1px; top: 19px; }
            54% { width: 0; left: 1px; top: 19px; }
            70% { width: 50px; left: -8px; top: 37px; }
            84% { width: 17px; left: 21px; top: 48px; }
            100% { width: 25px; left: 14px; top: 46px; }
        }
        @keyframes icon-line-long {
            0% { width: 0; right: 46px; top: 54px; }
            65% { width: 0; right: 46px; top: 54px; }
            84% { width: 55px; right: 0px; top: 35px; }
            100% { width: 47px; right: 8px; top: 38px; }
        }
    </style>
</x-layouts.app>
