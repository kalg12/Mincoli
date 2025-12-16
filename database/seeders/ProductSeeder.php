<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No hay categorías. Ejecuta CategorySeeder primero.');
            return;
        }

        $products = [
            // Dulces
            [
                'category' => 'dulces',
                'name' => 'Dulces Vero Mango',
                'description' => 'Deliciosos dulces enchilados sabor mango. Perfectos para cualquier ocasión.',
                'price' => 35.00,
                'cost' => 20.00,
                'is_featured' => true,
            ],
            [
                'category' => 'dulces',
                'name' => 'Paletas Payaso',
                'description' => 'Las clásicas paletas mexicanas de tutti frutti con goma de mascar.',
                'price' => 8.00,
                'cost' => 4.50,
                'is_featured' => false,
            ],

            // Chocolates
            [
                'category' => 'chocolates',
                'name' => 'Kit Kat White Fudge',
                'description' => 'Chocolate blanco Kit Kat con delicioso sabor a fudge.',
                'price' => 45.00,
                'cost' => 28.00,
                'is_featured' => true,
            ],
            [
                'category' => 'chocolates',
                'name' => 'Misuelito Duo',
                'description' => 'Chocolate mexicano con leche, delicioso y cremoso.',
                'price' => 38.00,
                'cost' => 22.00,
                'is_featured' => true,
            ],
            [
                'category' => 'chocolates',
                'name' => 'Oreo White Fudge',
                'description' => 'Galletas Oreo cubiertas de chocolate blanco sabor fudge.',
                'price' => 42.00,
                'cost' => 25.00,
                'is_featured' => true,
            ],

            // Botanas
            [
                'category' => 'botanas',
                'name' => 'Zumba Roll',
                'description' => 'Deliciosas botanas enrolladas con chile y limón.',
                'price' => 32.00,
                'cost' => 18.00,
                'is_featured' => false,
            ],
            [
                'category' => 'botanas',
                'name' => 'Chips Jalapeño',
                'description' => 'Papas fritas con sabor a jalapeño picante.',
                'price' => 28.00,
                'cost' => 15.00,
                'is_featured' => false,
            ],

            // Galletas
            [
                'category' => 'galletas',
                'name' => 'Príncipe Original',
                'description' => 'Galletas de chocolate rellenas de crema, clásico mexicano.',
                'price' => 25.00,
                'cost' => 14.00,
                'is_featured' => false,
            ],
            [
                'category' => 'galletas',
                'name' => 'Galletas Marías',
                'description' => 'Las tradicionales galletas marías, perfectas para el café.',
                'price' => 18.00,
                'cost' => 10.00,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->where('slug', $productData['category'])->first();

            if (!$category) {
                continue;
            }

            $productName = $productData['name'];

            Product::create([
                'category_id' => $category->id,
                'name' => $productName,
                'slug' => Str::slug($productName),
                'description' => $productData['description'],
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'barcode' => rand(1000000000000, 9999999999999),
                'price' => $productData['price'],
                'cost' => $productData['cost'],
                'iva_rate' => 16.00,
                'is_active' => true,
                'is_featured' => $productData['is_featured'],
            ]);
        }

        $this->command->info('Productos creados exitosamente.');
    }
}
