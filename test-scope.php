<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST SCOPE ACTIVE ===\n\n";

// Probar el scope
$banners = App\Models\Banner::active()->orderBy('position')->get();
echo "Banners usando scope active(): " . $banners->count() . "\n\n";

foreach($banners as $b) {
    echo "- {$b->title} (Activo: " . ($b->is_active ? 'SI' : 'NO') . ")\n";
}
