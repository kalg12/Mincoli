    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center">
                <i class="fas fa-file-excel text-green-600 mr-3"></i> 
                Carga Masiva de Asignaciones
            </h1>
            <a href="{{ route('dashboard.assignments.index') }}" class="inline-flex items-center px-4 py-2 border border-zinc-300 rounded-md shadow-sm text-sm font-medium text-zinc-700 bg-white hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 silver-btn">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
            </a>
        </div>

        <form action="{{ route('dashboard.assignments.store') }}" method="POST" id="batch-form">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Header Info - Spreadsheet Style Header -->
                <div class="p-6 rounded-xl bg-zinc-900 text-white shadow-xl border border-zinc-700 flex justify-between items-center">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400">Fecha de Corte / Asignación</label>
                        <input type="date" name="assigned_at" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-zinc-700 bg-zinc-800 text-white shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                    </div>
                    <div class="flex items-end space-x-3">
                        <button type="button" onclick="addRow(10)" class="px-4 py-2 bg-green-600/20 text-green-400 border border-green-600/30 rounded-lg text-sm hover:bg-green-600/30 transition-all font-bold">
                            + 10 Filas
                        </button>
                    </div>
                </div>

                <!-- Excel Table -->
                <div class="rounded-xl border border-zinc-200 bg-white shadow-2xl dark:border-zinc-700 dark:bg-zinc-900 overflow-hidden">
                    <table class="w-full text-left text-sm border-collapse" id="excel-table">
                        <thead class="bg-zinc-900 text-white text-[10px] font-black uppercase tracking-widest border-b border-zinc-700">
                            <tr>
                                <th class="px-3 py-4 border-r border-zinc-700 min-w-[180px]">Vendedor / Responsable</th>
                                <th class="px-3 py-4 border-r border-zinc-700 min-w-[220px]">Producto (Seleccionar)</th>
                                <th class="px-3 py-4 border-r border-zinc-700 w-24 text-center">Cant.</th>
                                <th class="px-3 py-4 border-r border-zinc-700 w-32 text-center text-pink-400 font-black">Precio (Total)</th>
                                <th class="px-3 py-4 border-r border-zinc-700 w-28 text-center bg-zinc-800">Corte (Base)</th>
                                <th class="px-3 py-4 border-r border-zinc-700 w-24 text-center bg-zinc-800">IVA (16%)</th>
                                <th class="px-3 py-4 border-r border-zinc-700 w-40">LOB / Socio</th>
                                <th class="px-3 py-4 w-12 text-center text-red-500 bg-zinc-800"><i class="fas fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <!-- Rows will be added dynamically -->
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center py-4">
                    <button type="button" onclick="addRow(1)" class="inline-flex items-center px-6 py-3 border-2 border-dashed border-zinc-300 dark:border-zinc-700 text-sm font-bold rounded-xl text-zinc-500 hover:text-pink-600 hover:border-pink-500 transition-all">
                        <i class="fas fa-plus mr-2"></i> Añadir otra fila
                    </button>
                    
                    <button type="submit" class="inline-flex items-center px-10 py-4 border border-transparent text-lg font-black rounded-xl shadow-2xl text-white bg-pink-600 hover:bg-pink-700 transform hover:scale-105 transition-all">
                        <i class="fas fa-cloud-upload-alt mr-3"></i> REGISTRAR CARGA
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .silver-btn { transition: all 0.2s; }
        .silver-btn:hover { transform: translateY(-1px); }
        input[readonly] { cursor: default; background-color: #cbd5e1 !important; font-weight: 800; color: #1e293b !important; }
        .dark input[readonly] { background-color: #334155 !important; color: #f1f5f9 !important; }
        select, input { outline: none !important; border-radius: 0 !important; }
        #excel-table td { padding: 0 !important; border: 2px solid #64748b; }
        #excel-table input, #excel-table select { 
            border: none; 
            padding: 0.75rem 0.5rem; 
            width: 100%; 
            background: transparent;
            font-size: 11px;
            font-weight: 900;
            color: #0f172a;
        }
        .dark #excel-table input, .dark #excel-table select { color: #f1f5f9; }
        #excel-table select { cursor: pointer; }
        #excel-table tr:hover { background-color: #f1f5f9; }
        .dark #excel-table tr:hover { background-color: #1e293b; }
        .header-cell { background-color: #f1f5f9; font-weight: 800; }
        .dark .header-cell { background-color: #0f172a; }
        #excel-table thead th { border: 2px solid #000; }
    </style>

    <script>
        let rowCount = 0;
        const products = @json($products);
        const users = @json($users);

        function addRow(count = 1) {
            for(let i=0; i<count; i++) {
                const tbody = document.querySelector('#excel-table tbody');
                const rowId = rowCount++;
                
                const tr = document.createElement('tr');
                tr.className = 'transition-colors bg-white dark:bg-zinc-900';
                tr.id = `row-${rowId}`;
                
                tr.innerHTML = `
                    <td class="border-2 border-zinc-950">
                        <select name="assignments[${rowId}][user_id]" class="font-black uppercase text-[10px] bg-white text-black h-full" required>
                            <option value="">[ VENDEDOR ]</option>
                            ${users.map(u => `<option value="${u.id}">${u.name}</option>`).join('')}
                        </select>
                    </td>
                    <td class="border-2 border-zinc-950">
                        <select name="assignments[${rowId}][product_id]" onchange="updateRowPrice(${rowId}, this)" class="font-black uppercase text-[10px] bg-white text-black h-full" required>
                            <option value="">[ PRODUCTO ]</option>
                            ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name}</option>`).join('')}
                        </select>
                    </td>
                    <td class="border-2 border-zinc-950">
                        <input type="number" name="assignments[${rowId}][quantity]" value="1" min="1" oninput="calculateRow(${rowId})" class="text-center font-black text-black bg-white" required>
                    </td>
                    <td class="border-2 border-zinc-950 bg-pink-100">
                        <input type="number" step="0.01" name="assignments[${rowId}][unit_price]" oninput="calculateRow(${rowId})" class="text-center font-black text-pink-700 bg-pink-100" required>
                    </td>
                    <td class="border-2 border-zinc-950 bg-zinc-200">
                        <input type="number" step="1" name="assignments[${rowId}][base_price]" class="text-center font-black text-zinc-700" readonly tabindex="-1">
                    </td>
                    <td class="border-2 border-zinc-950 bg-zinc-200">
                        <input type="number" step="0.01" name="assignments[${rowId}][iva_amount]" class="text-center font-black text-zinc-700" readonly tabindex="-1">
                    </td>
                    <td class="border-2 border-zinc-950">
                        <input type="text" name="assignments[${rowId}][partner_lob]" placeholder="SOCIO / LOB" class="font-black uppercase italic text-zinc-800 bg-white">
                    </td>
                    <td class="text-center border-2 border-zinc-950 bg-zinc-300">
                        <button type="button" onclick="removeRow(${rowId})" class="w-full h-full py-1 text-black hover:text-red-600 transition-colors">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(tr);
            }
        }

        function removeRow(rowId) {
            document.getElementById(`row-${rowId}`).remove();
        }

        function updateRowPrice(rowId, select) {
            const price = select.options[select.selectedIndex].dataset.price;
            const priceInput = document.querySelector(`input[name="assignments[${rowId}][unit_price]"]`);
            if (price) {
                priceInput.value = price;
                calculateRow(rowId);
            }
        }

        function calculateRow(rowId) {
            const priceInput = document.querySelector(`input[name="assignments[${rowId}][unit_price]"]`);
            const baseInput = document.querySelector(`input[name="assignments[${rowId}][base_price]"]`);
            const ivaInput = document.querySelector(`input[name="assignments[${rowId}][iva_amount]"]`);
            
            const total = parseFloat(priceInput.value) || 0;
            const base = Math.round(total / 1.16);
            const iva = total - base;
            
            baseInput.value = base;
            ivaInput.value = iva.toFixed(2);
        }

        // Add 5 rows by default
        window.onload = () => {
            for(let i=0; i<5; i++) addRow();
        }
    </script>
</x-layouts.app>
