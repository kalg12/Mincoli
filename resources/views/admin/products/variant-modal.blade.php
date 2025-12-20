<!-- Modal para agregar/editar variante -->
<div id="variantModal" class="hidden fixed inset-0 z-50">
    <div class="flex h-full w-full items-center justify-center p-4">
        <!-- Background overlay (below panel) -->
        <div onclick="closeVariantModal()" class="fixed inset-0 z-40 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75"></div>

        <!-- Modal panel (above overlay) -->
        <div class="relative z-50 w-full sm:max-w-lg rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-xl">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white" id="modalTitle">Agregar Variante</h3>
                    <button onclick="closeVariantModal()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="variantForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" id="variantId" name="variant_id" value="">

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Color</label>
                            <div class="flex gap-2">
                                <input type="color" name="color" id="colorPicker" class="h-10 w-16 rounded border border-zinc-200 dark:border-zinc-700 cursor-pointer">
                                <input type="text" name="color_text" id="colorText" placeholder="#ffffff" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Talla</label>
                            <input type="text" name="size" placeholder="XS, S, M, L, XL..." class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" required class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Código de Barras</label>
                            <input type="text" name="barcode" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Precio</label>
                            <input type="number" name="price" step="0.01" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" placeholder="Usa el precio del producto si está vacío">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stock <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" required min="0" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button onclick="submitVariantForm()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Guardar Variante
                </button>
                <button onclick="closeVariantModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-zinc-300 dark:border-zinc-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Color picker sync
    const colorPicker = document.getElementById('colorPicker');
    const colorText = document.getElementById('colorText');

    if (colorPicker) {
        colorPicker.addEventListener('input', (e) => {
            colorText.value = e.target.value;
        });

        colorText.addEventListener('input', (e) => {
            if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                colorPicker.value = e.target.value;
            }
        });
    }

    function openAddVariantModal() {
        const form = document.getElementById('variantForm');
        const modal = document.getElementById('variantModal');
        const modalTitle = document.getElementById('modalTitle');

        form.reset();
        form.querySelector('input[name="_method"]').value = 'POST';
        document.getElementById('variantId').value = '';
        modalTitle.textContent = 'Agregar Variante';

        // Obtener el ID del producto de la URL: /dashboard/products/{id}/edit
        const urlParts = window.location.pathname.split('/');
        const productId = urlParts[urlParts.length - 2]; // El -2 porque el último es 'edit'
        const action = `/dashboard/products/${productId}/variants`;
        form.action = action;

        modal.classList.remove('hidden');
    }

    function editVariant(variantId) {
        // Implementar cuando se incluya la API de edición
        alert('Editar variante - En desarrollo');
    }

    function deleteVariant(variantId) {
        if (confirm('¿Eliminar esta variante?')) {
            const productId = window.location.pathname.split('/').pop();
            fetch(`/dashboard/products/${productId}/variants/${variantId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(() => location.reload())
            .catch(err => console.error(err));
        }
    }

    function submitVariantForm() {
        document.getElementById('variantForm').submit();
    }

    function closeVariantModal() {
        document.getElementById('variantModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('variantModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeVariantModal();
        }
    });
</script>
