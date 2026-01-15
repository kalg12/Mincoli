<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Corte - Asignaciones</title>
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
    <div class="max-w-7xl mx-auto bg-white border border-zinc-200 p-8 md:p-16 print-shadow-none shadow-sm rounded-xl">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-zinc-900 pb-10">
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tighter text-zinc-900">Mincoli</h1>
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest mt-1">Reporte de Corte Semanal</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black uppercase text-zinc-900">Asignaciones</h2>
                @if($dateRange)
                <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mt-1">{{ $dateRange }}</p>
                @endif
                <p class="text-[10px] text-zinc-400 font-medium mt-1">Generado: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="mt-12 grid grid-cols-2 gap-12 pb-12 border-b border-zinc-100">
            <div>
                <h3 class="text-[10px] font-black uppercase text-zinc-400 mb-4 tracking-widest">Resumen General</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-zinc-500">Total de Asignaciones:</span>
                        <span class="font-black text-zinc-900">{{ $assignments->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-zinc-500">IVA Retenido (16%):</span>
                        <span class="font-black text-red-600">${{ number_format($corteSummary['iva'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-zinc-500">Base a Liquidar:</span>
                        <span class="font-black text-green-600">${{ number_format(collect($corteSummary['partners'])->sum(), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black border-t border-zinc-200 pt-2 mt-2">
                        <span>TOTAL:</span>
                        <span class="text-pink-600">${{ number_format($corteSummary['iva'] + collect($corteSummary['partners'])->sum(), 2) }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-[10px] font-black uppercase text-zinc-400 mb-4 tracking-widest">Desglose por Socio</h3>
                <div class="space-y-2">
                    @foreach($corteSummary['partners'] as $partner => $total)
                    <div class="flex justify-between text-sm">
                        <span class="font-bold text-zinc-700">{{ $partner ?: 'General' }}:</span>
                        <span class="font-black text-zinc-900">${{ number_format($total, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Assignments Table -->
        <div class="mt-12">
            <h3 class="text-[10px] font-black uppercase text-zinc-400 mb-6 tracking-widest">Detalle de Asignaciones</h3>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b-2 border-zinc-900">
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest">Responsable</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest">Producto</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest text-center">Cant.</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest text-right">Total</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest text-right">Base</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest text-right">IVA</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest">LOB</th>
                        <th class="py-3 text-[9px] font-black uppercase text-zinc-400 tracking-widest">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                    <tr class="border-b border-zinc-100">
                        <td class="py-3 text-xs font-bold text-zinc-900">{{ $assignment->user->name }}</td>
                        <td class="py-3 text-xs font-medium text-zinc-700">{{ $assignment->product->name }}</td>
                        <td class="py-3 text-center text-xs font-bold text-zinc-900">{{ $assignment->quantity }}</td>
                        <td class="py-3 text-right text-xs font-black text-pink-700">${{ number_format($assignment->unit_price, 2) }}</td>
                        <td class="py-3 text-right text-xs font-bold text-zinc-900">${{ number_format($assignment->base_price, 0) }}</td>
                        <td class="py-3 text-right text-xs font-bold text-zinc-700">${{ number_format($assignment->iva_amount, 2) }}</td>
                        <td class="py-3 text-[10px] font-bold text-zinc-600">{{ $assignment->partner_lob ?: '-' }}</td>
                        <td class="py-3 text-[9px] font-bold text-zinc-500">{{ $assignment->status_label }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-24 pt-10 border-t border-zinc-100 text-center">
            <p class="text-[10px] font-black uppercase text-zinc-400 tracking-[0.3em]">Reporte Generado por Mincoli</p>
            <p class="text-[9px] text-zinc-300 font-medium mt-2">Este documento es un reporte interno de asignaciones y cortes semanales.</p>
        </div>
    </div>

    <!-- Print Button (Hidden on Print) -->
    <div class="max-w-7xl mx-auto mt-8 flex justify-center gap-4 no-print">
        <button onclick="window.print()" class="bg-zinc-900 text-white px-8 py-3 rounded-full font-black uppercase text-xs hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-900/20">
            <i class="fas fa-print mr-2"></i> Imprimir / Guardar como PDF
        </button>
        <button onclick="window.close()" class="bg-zinc-200 text-zinc-600 px-8 py-3 rounded-full font-black uppercase text-xs hover:bg-zinc-300 transition-all">
            Cerrar Ventana
        </button>
    </div>
</body>
</html>
