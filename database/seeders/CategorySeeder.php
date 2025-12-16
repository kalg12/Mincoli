<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Dulces',
                'slug' => 'dulces',
                'is_active' => true,
            ],
            [
                'name' => 'Chocolates',
                'slug' => 'chocolates',
                'is_active' => true,
            ],
            [
                'name' => 'Botanas',
                'slug' => 'botanas',
                'is_active' => true,
            ],
            [
                'name' => 'Galletas',
                'slug' => 'galletas',
                'is_active' => true,
            ],
            [
                'name' => 'Bebidas',
                'slug' => 'bebidas',
                'is_active' => true,
            ],
            [
                'name' => 'Ropa',
                'slug' => 'ropa',
                'is_active' => true,
            ],
            [
                'name' => 'Joyería',
                'slug' => 'joyeria',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
