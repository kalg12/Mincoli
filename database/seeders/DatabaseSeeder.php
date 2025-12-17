<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario de prueba
        User::create([
            'name' => 'Admin Mincoli',
            'email' => 'admin@mincoli.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            PaymentMethodSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
