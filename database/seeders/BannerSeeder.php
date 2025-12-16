<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Regalos listos para enviar',
                'text' => 'Envíos rápidos y atención por WhatsApp o Messenger.',
                'link_url' => 'https://wa.me/525601110166',
                'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1600&q=80',
                'position' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Joyas y accesorios',
                'text' => 'Piezas especiales listas para sorprender.',
                'link_url' => 'https://wa.me/525601110166',
                'image_url' => 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=1600&q=80',
                'position' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Dulces favoritos',
                'text' => 'Tus sabores clásicos, directo a tu casa.',
                'link_url' => 'https://wa.me/525601110166',
                'image_url' => 'https://images.unsplash.com/photo-1505253758473-96b7015fcd40?auto=format&fit=crop&w=1600&q=80',
                'position' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
