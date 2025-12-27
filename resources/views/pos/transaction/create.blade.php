<x-layouts.app :title="__('POS - Nueva Transaccion')">
<div class="p-6 space-y-6  dark:bg-zinc-950 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-zinc-900 dark:text-zinc-100">Nueva Transaccion - Apartado</h1>

        <form action="{{ route('dashboard.pos.transaction.store', $session) }}" method="POST" class="rounded-xl border border-zinc-200 bg-white p-8 shadow dark:border-zinc-700 dark:bg-zinc-900 space-y-6">
            @csrf

            <div>
                <label class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Buscar cliente por telefono</label>
                <div class="flex gap-2">
                    <input type="tel" id="phoneLookup" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500" placeholder="Ej: 300, +57 300..." />
                    <button type="button" id="btnLookup" class="px-4 py-2 bg-zinc-200 text-zinc-800 rounded-lg font-semibold hover:bg-zinc-300 transition dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">Buscar</button>
                </div>
                <div id="lookupResults" class="mt-3 text-sm text-zinc-700 dark:text-zinc-300 space-y-2"></div>
            </div>

            <div>
                <label for="customer_id" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Cliente (Opcional)</label>
                <select name="customer_id" id="customer_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="" class="bg-white text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100">-- Seleccionar cliente existente --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" class="bg-white text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100">{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Para lives: usa telefono para evitar duplicados.</p>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Crear Cliente Rapido (si no aparece arriba)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer_name" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Nombre del Cliente</label>
                    <input type="text" name="customer_name" id="customer_name" placeholder="Juan Perez" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500">
                </div>
                <div>
                    <label for="customer_phone" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Telefono (obligatorio para nuevo)</label>
                    <input type="tel" name="customer_phone" id="customer_phone" placeholder="+57 300 123 4567" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500">
                </div>
            </div>

            <div>
                <label for="notes" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Notas (Opcional)</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Ej: Venta en vivo Instagram..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="shipping_contact_phone" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Telefono de envio (si difiere)</label>
                    <input type="tel" name="shipping_contact_phone" id="shipping_contact_phone" placeholder="Opcional" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500">
                </div>
                <div>
                    <label for="shipping_address" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Direccion de envio</label>
                    <textarea name="shipping_address" id="shipping_address" rows="2" placeholder="Calle, numero, ciudad" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500"></textarea>
                </div>
            </div>

            <div>
                <label for="shipping_notes" class="block font-semibold mb-2 text-zinc-800 dark:text-zinc-200">Notas de envio</label>
                <textarea name="shipping_notes" id="shipping_notes" rows="2" placeholder="Referencias, horario, etc." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:placeholder-zinc-500"></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition dark:bg-blue-500 dark:hover:bg-blue-600">
                    Crear Transaccion
                </button>
                <a href="{{ route('dashboard.pos.index') }}" class="flex-1 px-4 py-3 bg-zinc-200 text-zinc-800 rounded-lg font-semibold hover:bg-zinc-300 transition text-center dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const lookupBtn = document.getElementById('btnLookup');
const lookupInput = document.getElementById('phoneLookup');
const lookupResults = document.getElementById('lookupResults');
const customerSelect = document.getElementById('customer_id');
const customerNameInput = document.getElementById('customer_name');
const customerPhoneInput = document.getElementById('customer_phone');
const lookupUrl = "{{ route('dashboard.pos.customers.search') }}";

lookupBtn.addEventListener('click', async () => {
    const phone = lookupInput.value.trim();
    if (phone.length < 3) {
        lookupResults.textContent = 'Escribe al menos 3 caracteres de telefono.';
        return;
    }

    lookupResults.textContent = 'Buscando...';
    const resp = await fetch(`${lookupUrl}?phone=${encodeURIComponent(phone)}`);
    const customers = await resp.json();

    if (!customers.length) {
        lookupResults.textContent = 'Sin coincidencias. Puedes crear un cliente rapido.';
        return;
    }

    lookupResults.innerHTML = customers.map(c => `
        <button type="button" class="w-full text-left px-3 py-2 border rounded hover:bg-white transition"
            data-id="${c.id}" data-name="${c.name}" data-phone="${c.phone ?? ''}">
            ${c.name} (${c.phone ?? 'sin telefono'})
        </button>
    `).join('');

    lookupResults.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', () => {
            customerSelect.value = btn.dataset.id;
            customerNameInput.value = btn.dataset.name;
            customerPhoneInput.value = btn.dataset.phone;
        });
    });
});
</script>
</x-layouts.app>
