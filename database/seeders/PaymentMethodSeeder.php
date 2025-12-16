<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Tarjeta de Crédito/Débito',
                'code' => 'card',
                'description' => 'Paga con tu tarjeta de crédito o débito de forma segura.',
                'is_active' => true,
                'requires_verification' => false,
            ],
            [
                'name' => 'Transferencia Bancaria',
                'code' => 'bank_transfer',
                'description' => 'Realiza una transferencia directa a nuestra cuenta bancaria.',
                'is_active' => true,
                'requires_verification' => true,
            ],
            [
                'name' => 'OXXO',
                'code' => 'oxxo',
                'description' => 'Paga en efectivo en cualquier tienda OXXO.',
                'is_active' => true,
                'requires_verification' => false,
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'description' => 'Paga de forma segura con tu cuenta PayPal.',
                'is_active' => true,
                'requires_verification' => false,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('Métodos de pago creados exitosamente.');
    }
}
