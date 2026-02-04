<?php

namespace Database\Seeders;

use App\Models\LiveSession;
use App\Models\LiveProductHighlight;
use App\Models\Product;
use Illuminate\Database\Seeder;

class LiveSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear una transmisión activa para pruebas
        $activeLive = LiveSession::create([
            'title' => 'Especial de Verano 2026 - Productos Destacados',
            'platform' => 'Instagram Live',
            'live_url' => 'https://www.instagram.com/mincolimx/live/',
            'is_live' => true,
            'starts_at' => now()->subMinutes(15),
            'ends_at' => null,
        ]);

        // Obtener algunos productos para destacar
        $products = Product::where('is_active', true)
            ->limit(6)
            ->get();

        // Agregar productos destacados a la transmisión activa
        foreach ($products as $index => $product) {
            LiveProductHighlight::create([
                'live_session_id' => $activeLive->id,
                'product_id' => $product->id,
                'variant_id' => null,
                'description' => 'Producto especial para esta transmisión en vivo',
                'position' => $index + 1,
            ]);
        }

        // Crear una transmisión programada para el futuro
        $scheduledLive = LiveSession::create([
            'title' => 'Gran Liquidación de Fin de Semana',
            'platform' => 'Facebook Live',
            'live_url' => 'https://www.facebook.com/MincoliMx/live/',
            'is_live' => false,
            'starts_at' => now()->addDays(2),
            'ends_at' => null,
        ]);

        // Agregar productos a la transmisión programada
        $moreProducts = Product::where('is_active', true)
            ->skip(6)
            ->limit(4)
            ->get();

        foreach ($moreProducts as $index => $product) {
            LiveProductHighlight::create([
                'live_session_id' => $scheduledLive->id,
                'product_id' => $product->id,
                'variant_id' => null,
                'description' => 'Oferta especial en esta transmisión',
                'position' => $index + 1,
            ]);
        }

        $this->command->info('Live sessions creadas exitosamente!');
        $this->command->info('Transmisión activa: ' . $activeLive->title);
        $this->command->info('Transmisión programada: ' . $scheduledLive->title);
    }
}
