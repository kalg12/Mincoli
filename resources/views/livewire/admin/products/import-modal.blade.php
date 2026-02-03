<?php

use function Livewire\Volt\{state, usesFileUploads};
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\InventoryMovement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

usesFileUploads();

state([
    'file' => null,
    'step' => 'upload', // upload, preview, processing, finished
    'rows' => [],
    'headers' => [],
    'totalRows' => 0,
    'processedRows' => 0,
    'errors' => [],
    'showModal' => false,
    'editingIndex' => null,
    'editingRow' => [],
    'overrides' => [], // index => modified row data
]);

$resetAll = function () {
    $this->file = null;
    $this->step = 'upload';
    $this->rows = [];
    $this->headers = [];
    $this->totalRows = 0;
    $this->processedRows = 0;
    $this->errors = [];
    $this->editingIndex = null;
    $this->editingRow = [];
    $this->overrides = [];
};

$close = function() {
    $this->showModal = false;
    $this->resetAll();
};

$uploadFile = function () {
    try {
        $this->errors = [];
        $this->validate([
            'file' => 'required|max:10240',
        ]);

        if (!$this->file) {
             throw new \Exception("No se ha seleccionado el archivo correctamente.");
        }

        $path = $this->file->getRealPath();
        $data = [];
        if (($handle = fopen($path, "r")) !== FALSE) {
            $rawHeaders = fgetcsv($handle, 1000, ",");
            
            if (!$rawHeaders) {
                fclose($handle);
                throw new \Exception("El archivo CSV parece estar vacío.");
            }

            // Convert headers to UTF-8
            $this->headers = array_map(function($h) {
                return mb_convert_encoding(trim($h), 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            }, $rawHeaders);

            $count = 0;
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($this->headers) == count($row)) {
                    if ($count < 50) { 
                        // Convert row values to UTF-8
                        $utf8Row = array_map(function($v) {
                            return mb_convert_encoding(trim($v), 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
                        }, $row);
                        $data[] = array_combine($this->headers, $utf8Row);
                    }
                    $count++;
                }
            }
            fclose($handle);
            $this->totalRows = $count;
        }

        $this->rows = $data;
        $this->overrides = [];
        $this->step = 'preview';
    } catch (\Throwable $e) {
        $this->errors[] = "Error de lectura: " . $e->getMessage();
        $this->step = 'upload';
    }
};

$editRow = function ($index) {
    $this->editingIndex = $index;
    $this->editingRow = $this->rows[$index];
};

$saveRow = function () {
    if ($this->editingIndex !== null) {
        $this->rows[$this->editingIndex] = $this->editingRow;
        $this->overrides[$this->editingIndex] = $this->editingRow;
        $this->editingIndex = null;
    }
};

$cancelEdit = function () {
    $this->editingIndex = null;
};

$downloadBackup = function () {
    $headers = $this->headers;
    $callback = function() use ($headers) {
        $file = fopen('php://output', 'w');
        // Add BOM for Excel compatibility with UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($file, $headers);
        
        // We only have the first 50 rows in state currently.
        // If we want a FULL backup, we'd need to re-read the file.
        // But the user usually wants to download what they changed.
        foreach ($this->rows as $row) {
            fputcsv($file, array_values($row));
        }
        fclose($file);
    };

    return response()->streamDownload($callback, 'mincoli_import_preview_backup.csv', [
        'Content-Type' => 'text/csv; charset=utf-8',
    ]);
};

$downloadAndStoreDriveImage = function (?string $url) {
    if (!$url) {
        return null;
    }

    $direct = ProductImage::resolveDriveUrl($url);
    if (!$direct) {
        return null;
    }

    try {
        $response = Http::timeout(15)->get($direct);
        if (!$response->ok()) {
            return null;
        }

        $contentType = $response->header('Content-Type');
        $ext = 'jpg';
        if ($contentType === 'image/png') {
            $ext = 'png';
        } elseif ($contentType === 'image/webp') {
            $ext = 'webp';
        } elseif (str_contains($contentType, 'image/jpeg') || str_contains($contentType, 'image/jpg')) {
            $ext = 'jpg';
        } elseif ($contentType === 'image/avif') {
            $ext = 'avif';
        }

        $filename = 'product-images/' . uniqid('import_', true) . '.' . $ext;
        
        Storage::disk('public')->put($filename, $response->body());

        return Storage::disk('public')->url($filename);
    } catch (\Throwable $e) {
        return null; // Silent skip for images
    }
};

$handleImages = function ($product, $variant, $row, &$processedUrls) {
    if (!empty($row['Imagenes'])) {
        // Clear existing images for this Specific item (Product or Variant) 
        // to avoid duplicates on re-import
        if ($variant) {
            $variant->images()->delete();
        } else {
            $product->images()->whereNull('variant_id')->delete();
        }

        $imageUrls = explode(',', $row['Imagenes']);
        $pos = ($product->images()->max('position') ?? -1) + 1;
        foreach ($imageUrls as $url) {
            $url = trim($url);
            if (!$url) continue;
            
            // Generate a unique key for product+url to avoid adding same image twice to same product
            $urlKey = $product->id . '_' . $url;
            if (isset($processedUrls[$urlKey])) continue;

            $storedUrl = $this->downloadAndStoreDriveImage($url);
            if ($storedUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'url' => $storedUrl,
                    'position' => $pos++,
                ]);
                $processedUrls[$urlKey] = true;
            }
        }
    }
};

$startImport = function () use ($handleImages) {
    $this->step = 'processing';
    $this->processedRows = 0;
    $this->errors = [];
    $processedUrls = []; // Tracked per import run
    
    try {
        $path = $this->file->getRealPath();
        
        if (($handle = fopen($path, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ","); // Skip headers
            
            $count = 0;
            while (($rowData = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($this->headers) != count($rowData)) {
                    $count++;
                    continue;
                }
                
                // Convert values to UTF-8
                $rowData = array_map(function($v) {
                    return mb_convert_encoding(trim($v), 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
                }, $rowData);
                
                $row = array_combine($this->headers, $rowData);
                
                // Apply overrides from the preview UI
                if (isset($this->overrides[$count])) {
                    $row = array_merge($row, $this->overrides[$count]);
                }
                try {
                            DB::transaction(function () use ($row, $handleImages, &$processedUrls) {
                                // Find or create category
                                $categoryName = $row['Categoria'] ?? 'Sin Categoria';
                                $category = Category::firstOrCreate(
                                    ['name' => $categoryName],
                                    ['slug' => Str::slug($categoryName), 'is_active' => true]
                                );

                                $subCategoryId = null;
                                if (!empty($row['Subcategoria'])) {
                                    $subCategory = Category::firstOrCreate(
                                        ['name' => $row['Subcategoria'], 'parent_id' => $category->id],
                                        ['slug' => Str::slug($row['Subcategoria']), 'is_active' => true]
                                    );
                                    $subCategoryId = $subCategory->id;
                                }

                                $sku = $row['SKU'] ?? null;
                                $name = $row['Nombre'] ?? null;
                                $targetStock = intval($row['Stock'] ?? 0);
                                $price = floatval($row['Precio'] ?? 0);

                                if (!$sku) throw new \Exception("El SKU es obligatorio.");

                                // Logic: If a product with this SKU exists, update it.
                                // If not, check if a variant with this SKU exists.
                                // If neither, check if a product with the SAME NAME exists -> create as variant of that.
                                // Otherwise, create as a new main product.

                                $existingProduct = Product::where('sku', $sku)->first();
                                $existingVariant = ProductVariant::where('sku', $sku)->first();

                                if ($existingVariant) {
                                    // Update existing variant
                                    $parent = $existingVariant->product;
                                    $oldStock = $existingVariant->stock;
                                    $existingVariant->update([
                                        'name' => $name ?: $existingVariant->name,
                                        'size' => $row['Talla'] ?? $existingVariant->size,
                                        'color' => $row['Color'] ?? $existingVariant->color,
                                        'material' => $row['Material'] ?? $existingVariant->material,
                                        'price' => $price > 0 ? $price : ($existingVariant->price ?: $parent->price),
                                        'stock' => $targetStock,
                                    ]);

                                    $diff = $targetStock - $oldStock;
                                    if ($diff != 0) {
                                        InventoryMovement::create([
                                            'product_id' => $parent->id,
                                            'variant_id' => $existingVariant->id,
                                            'type' => $diff > 0 ? 'in' : 'out',
                                            'quantity' => abs($diff),
                                            'reason' => 'Importación masiva (Actualización)',
                                            'created_by' => Auth::id(),
                                        ]);
                                    }
                                    $handleImages($existingVariant->product, $existingVariant, $row, $processedUrls);
                                } elseif ($existingProduct) {
                                    // Update existing main product
                                    $oldStock = $existingProduct->stock;
                                    $existingProduct->update([
                                        'name' => $name ?: $existingProduct->name,
                                        'brand' => $row['Marca'] ?? $existingProduct->brand,
                                        'description' => $row['Descripcion'] ?? $existingProduct->description,
                                        'category_id' => $category->id,
                                        'subcategory_id' => $subCategoryId,
                                        'price' => $price > 0 ? $price : $existingProduct->price,
                                        'stock' => $targetStock,
                                    ]);

                                    $diff = $targetStock - $oldStock;
                                    if ($diff != 0) {
                                        InventoryMovement::create([
                                            'product_id' => $existingProduct->id,
                                            'type' => $diff > 0 ? 'in' : 'out',
                                            'quantity' => abs($diff),
                                            'reason' => 'Importación masiva (Actualización)',
                                            'created_by' => Auth::id(),
                                        ]);
                                    }
                                    $handleImages($existingProduct, null, $row, $processedUrls);
                                } else {
                                    // NEW ITEM
                                    // Check if we should treat it as a variant of an existing product name
                                    $parentByName = Product::where('name', $name)->first();

                                    if ($parentByName) {
                                        // Subsequent variant
                                        $variant = $parentByName->variants()->create([
                                            'sku' => $sku,
                                            'name' => $name,
                                            'size' => $row['Talla'] ?? null,
                                            'color' => $row['Color'] ?? null,
                                            'material' => $row['Material'] ?? null,
                                            'price' => $price > 0 ? $price : $parentByName->price,
                                            'stock' => $targetStock,
                                        ]);

                                        InventoryMovement::create([
                                            'product_id' => $parentByName->id,
                                            'variant_id' => $variant->id,
                                            'type' => 'in',
                                            'quantity' => $targetStock,
                                            'reason' => 'Importación (Nueva Variante)',
                                            'created_by' => Auth::id(),
                                        ]);
                                        $handleImages($parentByName, $variant, $row, $processedUrls);
                                    } else {
                                        // First time seeing this name -> Create Product AND First Variant
                                        $product = Product::create([
                                            'sku' => $sku,
                                            'name' => $name ?: 'Nuevo Producto',
                                            'slug' => Str::slug($name ?: 'nuevo-producto-' . uniqid()),
                                            'brand' => $row['Marca'] ?? null,
                                            'description' => $row['Descripcion'] ?? '',
                                            'category_id' => $category->id,
                                            'subcategory_id' => $subCategoryId,
                                            'price' => $price,
                                            'stock' => $targetStock,
                                            'status' => 'published',
                                            'is_active' => true,
                                        ]);

                                        // Creating the first variant explicitly so it can have its own images/stock record
                                        $variant = $product->variants()->create([
                                            'sku' => $sku,
                                            'name' => $name,
                                            'size' => $row['Talla'] ?? null,
                                            'color' => $row['Color'] ?? null,
                                            'material' => $row['Material'] ?? null,
                                            'price' => $price,
                                            'stock' => $targetStock,
                                        ]);

                                        InventoryMovement::create([
                                            'product_id' => $product->id,
                                            'variant_id' => $variant->id,
                                            'type' => 'in',
                                            'quantity' => $targetStock,
                                            'reason' => 'Importación (Nuevo Producto)',
                                            'created_by' => Auth::id(),
                                        ]);
                                        
                                        // Associating images to both the product gallery and specific variant
                                        $handleImages($product, $variant, $row, $processedUrls);
                                    }
                                }
                            });
                        } catch (\Throwable $e) {
                            $this->errors[] = "Error en SKU {$row['SKU']}: " . $e->getMessage();
                        }

                $this->processedRows++;
                $count++;
            }
            fclose($handle);
        }
    } catch (\Throwable $e) {
        $this->errors[] = "Error general: " . $e->getMessage();
    }

    $this->step = 'finished';
};

$downloadTemplate = function() {
    $headers = ['Nombre', 'SKU', 'Marca', 'Categoria', 'Subcategoria', 'Precio', 'Stock', 'Descripcion', 'Color', 'Material', 'Talla', 'Imagenes'];
    $callback = function() use ($headers) {
        $file = fopen('php://output', 'w');
        // Add BOM for Excel compatibility with UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($file, $headers);
        
        // Example 1: Product with variants (linked by EXACT SAME Name)
        // Row 1: First variant. Put the main product photos here.
        fputcsv($file, ['Anillo Luna', 'AN-LU-P', 'Mincoli', 'Joyería', 'Anillos', '1200.00', '10', 'Hermoso diseño de luna', 'Plata', 'Plata 925', 'P', 'https://tu-sitio.com/foto-anillo-plata.jpg']);
        
        // Row 2: Second variant (Same Name). Put the specific photos for this color/talla here.
        fputcsv($file, ['Anillo Luna', 'AN-LU-O', '', '', '', '1500.00', '5', '', 'Oro', 'Oro 14k', 'P', 'https://tu-sitio.com/foto-anillo-oro.jpg']);
        
        // Example 2: Simple product (just one row)
        fputcsv($file, ['Collar Estelar', 'COL-EST', 'Mincoli', 'Joyería', 'Collares', '2500.00', '3', 'Collar de lujo', 'Dorado', 'Oro 24k', 'U', 'https://tu-sitio.com/collar.jpg']);
        
        fclose($file);
    };

    return response()->streamDownload($callback, 'plantilla_mincoli.csv', [
        'Content-Type' => 'text/csv; charset=utf-8',
    ]);
};

?>

<div x-data="{ open: @entangle('showModal') }" 
     x-on:open-import-modal.window="open = true"
     x-show="open" 
     class="fixed inset-0 z-[100] overflow-y-auto" 
     x-cloak
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity bg-zinc-950/80 backdrop-blur-sm" 
             @click="open = false; $wire.close()"
             aria-hidden="true">
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Content -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative inline-block align-bottom bg-white dark:bg-zinc-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-zinc-200 dark:border-zinc-800">
            
            <div class="px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white" id="modal-title">
                                Importar Productos
                            </h3>
                            <button wire:click="downloadTemplate" class="text-sm text-pink-600 dark:text-pink-400 hover:text-pink-700 font-bold flex items-center gap-2 transition">
                                <i class="fas fa-download"></i> Descargar Plantilla
                            </button>
                        </div>

                         @if($step === 'upload')
                            <div class="mt-4" 
                                 x-data="{ isDragging: false }" 
                                 @dragover.prevent="isDragging = true" 
                                 @dragleave.prevent="isDragging = false" 
                                 @drop.prevent="isDragging = false; 
                                              if($event.dataTransfer.files.length > 0) {
                                                  $wire.upload('file', $event.dataTransfer.files[0])
                                              }">
                                
                                @if(count($errors) > 0)
                                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 rounded-xl text-xs text-red-600 dark:text-red-400">
                                        <div class="font-bold flex items-center gap-2 mb-1">
                                            <i class="fas fa-exclamation-triangle"></i> No se pudo procesar:
                                        </div>
                                        <ul class="list-disc pl-4">
                                            @foreach($errors as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="flex items-center justify-center w-full">
                                    <label :class="{ 'border-pink-500 bg-pink-100 dark:bg-pink-900/40 shadow-inner scale-[0.99]': isDragging }" 
                                           class="flex flex-col items-center justify-center w-full h-64 border-2 border-zinc-200 dark:border-zinc-700 border-dashed rounded-2xl cursor-pointer bg-zinc-50 dark:bg-zinc-800/50 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all group relative">
                                        
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div class="w-16 h-16 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                                <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                            </div>
                                            <p class="mb-2 text-base text-zinc-700 dark:text-zinc-300"><span class="font-bold">Selecciona un archivo</span> o arrástralo aquí</p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-500">Solo archivos CSV (Máx. 10MB)</p>
                                        </div>
                                        <input type="file" wire:model="file" x-ref="fileInput" class="hidden" />

                                        <!-- Centered Loading Overlay -->
                                        <div wire:loading wire:target="file" class="absolute inset-0 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm rounded-2xl flex items-center justify-center z-50">
                                            <div class="flex flex-col items-center">
                                                <div class="w-12 h-12 border-4 border-pink-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                                                <p class="text-base font-bold text-zinc-900 dark:text-zinc-100">Subiendo archivo...</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                @if($file)
                                    <div class="mt-6 flex items-center justify-between p-4 bg-pink-50 dark:bg-pink-900/10 border border-pink-100 dark:border-pink-900/20 rounded-xl animate-in fade-in slide-in-from-top-2">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-pink-600 p-2 rounded-lg text-white">
                                                <i class="fas fa-file-csv text-xl"></i>
                                            </div>
                                            <div>
                                                <span class="text-sm text-zinc-900 dark:text-zinc-100 font-bold block">{{ $file->getClientOriginalName() }}</span>
                                                <span class="text-[10px] text-pink-600 dark:text-pink-400 uppercase font-bold tracking-wider">Listo para analizar</span>
                                            </div>
                                        </div>
                                        <button wire:click="uploadFile" 
                                                wire:loading.attr="disabled"
                                                class="bg-pink-600 text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-pink-700 transition shadow-lg shadow-pink-200 dark:shadow-none flex items-center gap-3 active:scale-95 disabled:opacity-50">
                                            <span wire:loading.remove wire:target="uploadFile">Analizar Datos</span>
                                            <span wire:loading wire:target="uploadFile">Leyendo CSV...</span>
                                            <i wire:loading.remove wire:target="uploadFile" class="fas fa-arrow-right"></i>
                                            <i wire:loading wire:target="uploadFile" class="fas fa-circle-notch animate-spin"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @elseif($step === 'preview')
                            <div class="mt-4">
                                 <div class="bg-zinc-100 dark:bg-zinc-800 rounded-xl p-4 mb-6 flex items-center justify-between border border-zinc-200 dark:border-zinc-700">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-zinc-200 dark:bg-zinc-700 p-3 rounded-full">
                                            <i class="fas fa-search text-zinc-600 dark:text-zinc-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-zinc-900 dark:text-zinc-100 font-bold">Resumen del archivo</p>
                                            <p class="text-xs text-zinc-600 dark:text-zinc-400">Se han detectado <strong>{{ $totalRows }}</strong> registros para importar.</p>
                                        </div>
                                    </div>
                                    <button wire:click="downloadBackup" class="text-xs font-bold text-pink-600 dark:text-pink-400 bg-white dark:bg-zinc-900 px-4 py-2 rounded-lg border border-pink-100 dark:border-pink-900/30 hover:bg-pink-50 transition flex items-center gap-2">
                                        <i class="fas fa-file-export"></i> Descargar Respaldo (CSV)
                                    </button>
                                </div>

                                <div class="max-h-80 overflow-auto border border-zinc-200 dark:border-zinc-700 rounded-xl relative">
                                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                                        <thead class="bg-zinc-50 dark:bg-zinc-800/80 sticky top-0 z-10">
                                            <tr>
                                                <th class="px-3 py-3 text-left text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider bg-zinc-50 dark:bg-zinc-800">Acción</th>
                                                @foreach($headers as $header)
                                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider bg-zinc-50 dark:bg-zinc-800">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-100 dark:divide-zinc-800">
                                            @foreach($rows as $index => $row)
                                                <tr wire:key="import-row-{{ $index }}" 
                                                    class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 {{ $editingIndex === $index ? 'bg-pink-50/50 dark:bg-pink-900/10' : '' }}">
                                                    <td class="px-3 py-2 whitespace-nowrap text-xs">
                                                        @if($editingIndex === $index)
                                                            <div class="flex gap-2">
                                                                <button wire:click="saveRow" class="text-emerald-600 hover:text-emerald-700 p-1" title="Guardar">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button wire:click="cancelEdit" class="text-zinc-400 hover:text-zinc-600 p-1" title="Cancelar">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <button wire:click="editRow({{ $index }})" class="text-pink-600 dark:text-pink-400 hover:text-pink-700 p-1" title="Editar fila">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                    @foreach($headers as $header)
                                                        <td class="px-3 py-2 whitespace-nowrap text-xs text-zinc-600 dark:text-zinc-400">
                                                            @if($editingIndex === $index)
                                                                <input type="text" 
                                                                       wire:model="editingRow.{{ $header }}" 
                                                                       class="w-full bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 rounded text-xs px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
                                                            @else
                                                                {{ $row[$header] ?? '' }}
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-8 flex items-center justify-between">
                                    <button wire:click="resetAll" class="text-sm text-zinc-500 dark:text-zinc-400 font-bold hover:text-zinc-700 dark:hover:text-zinc-200 transition">
                                        <i class="fas fa-arrow-left mr-2"></i> Cambiar archivo
                                    </button>
                                    <button wire:click="startImport" class="bg-pink-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-pink-700 transition shadow-lg shadow-pink-200 dark:shadow-none flex items-center gap-2">
                                        Confirmar Importación <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        @elseif($step === 'processing')
                            <div class="mt-10 py-16 text-center">
                                <div class="relative w-24 h-24 mx-auto mb-6">
                                    <div class="absolute inset-0 rounded-full border-4 border-zinc-100 dark:border-zinc-800"></div>
                                    <div class="absolute inset-0 rounded-full border-4 border-pink-600 border-t-transparent animate-spin"></div>
                                </div>
                                <h4 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">Procesando...</h4>
                                <p class="text-zinc-500 dark:text-zinc-400 mb-8">Por favor, no cierres esta ventana.</p>
                                
                                <div class="max-w-md mx-auto">
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 rounded-full h-3 mb-3 overflow-hidden">
                                        <div class="bg-pink-600 h-full rounded-full transition-all duration-500 ease-out" style="width: {{ ($processedRows / $totalRows) * 100 }}%"></div>
                                    </div>
                                    <p class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                        {{ $processedRows }} de {{ $totalRows }} completado
                                    </p>
                                </div>
                            </div>
                        @elseif($step === 'finished')
                            <div class="mt-4 text-center py-10">
                                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 mb-6">
                                    <svg class="h-10 w-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h4 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">¡Todo listo!</h4>
                                <p class="mt-2 text-base text-zinc-600 dark:text-zinc-400">La importación se ha completado con éxito.</p>
                                
                                @if(count($errors) > 0)
                                    <div class="mt-6 text-left p-5 bg-red-50 dark:bg-red-900/10 rounded-2xl border border-red-100 dark:border-red-900/20">
                                        <h5 class="text-sm font-bold text-red-800 dark:text-red-400 mb-3 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i> Errores encontrados ({{ count($errors) }}):
                                        </h5>
                                        <ul class="text-xs text-red-700 dark:text-red-300/80 list-disc pl-5 max-h-40 overflow-auto space-y-1">
                                            @foreach($errors as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                                    <button wire:click="downloadBackup" class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-bold text-zinc-600 dark:text-zinc-400 bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition border border-zinc-200 dark:border-zinc-800">
                                        <i class="fas fa-file-download mr-2"></i> Guardar Respaldo
                                    </button>
                                    <a href="{{ route('dashboard.inventory.movements') }}" class="inline-flex items-center justify-center px-8 py-3 rounded-xl text-sm font-bold text-pink-600 dark:text-pink-400 bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30 transition border border-pink-100 dark:border-pink-900/30">
                                        <i class="fas fa-history mr-2"></i> Ver Historial
                                    </a>
                                    <button wire:click="close" class="bg-zinc-900 dark:bg-white dark:text-zinc-900 text-white px-10 py-3 rounded-xl font-bold hover:bg-zinc-800 dark:hover:bg-zinc-200 transition shadow-xl">
                                        Finalizar
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-6 py-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                <button type="button" @click="open = false; $wire.close()" class="text-sm font-bold text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition">
                    Cerrar ventana
                </button>
            </div>
        </div>
    </div>
</div>
