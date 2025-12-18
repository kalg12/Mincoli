<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configuración de IVA
        SiteSetting::updateOrCreate(
            ['group' => 'store', 'key' => 'show_iva'],
            ['value' => true]
        );
    }
}
