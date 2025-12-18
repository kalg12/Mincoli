<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO DE BANNERS ===\n\n";

$banners = App\Models\Banner::all();
echo "Total banners en DB: " . $banners->count() . "\n\n";

foreach($banners as $banner) {
    echo "ID: {$banner->id}\n";
    echo "Título: {$banner->title}\n";
    echo "Activo: " . ($banner->is_active ? 'SÍ' : 'NO') . "\n";
    echo "Imagen URL: " . ($banner->image_url ?? 'null') . "\n";
    echo "Posición: {$banner->position}\n";
    echo "---\n";
}

echo "\nBanners activos: " . App\Models\Banner::where('is_active', true)->count() . "\n";

$activeBanners = App\Models\Banner::active()->get();
echo "Banners activos (usando scope): " . $activeBanners->count() . "\n";
