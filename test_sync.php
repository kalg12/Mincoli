<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

echo "Starting Test...\n";

// Create a product
$product = Product::create([
    'name' => 'Test Sync Product',
    'slug' => 'test-sync-product-' . Str::random(5),
    'category_id' => 1,
    'price' => 100,
    'stock' => 0,
    'status' => 'published'
]);

echo "Created Product ID: {$product->id}. Initial Stock: {$product->stock}\n";

// Create a variant with stock 10
$variant = ProductVariant::create([
    'product_id' => $product->id,
    'name' => 'Variant 1',
    'sku' => 'TEST-SKU-' . Str::random(5),
    'stock' => 10,
    'price' => 100
]);

$product->refresh();
echo "Created Variant (Stock 10). Product Stock: {$product->stock}\n";

if ($product->stock !== 10) {
    echo "FAIL: Product stock should be 10.\n";
    $product->forceDelete();
    exit(1);
}

// Update variant stock to 20
$variant->update(['stock' => 20]);
$product->refresh();
echo "Updated Variant (Stock 20). Product Stock: {$product->stock}\n";

if ($product->stock !== 20) {
    echo "FAIL: Product stock should be 20.\n";
    $product->forceDelete();
    exit(1);
}

// Delete product to clean up
$product->forceDelete();
echo "Test Passed.\n";
