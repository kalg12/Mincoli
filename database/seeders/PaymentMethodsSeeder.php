<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Mercado Pago',
                'code' => 'mercadopago',
                'description' => 'Paga con tarjeta de crédito, débito o en efectivo en OXXO.',
                'is_active' => true,
                'settings' => json_encode([
                    'public_key' => '',
                    'access_token' => '',
                    'environment' => 'sandbox', // sandbox or production
                ]),
                'instructions' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transferencia Bancaria',
                'code' => 'transfer',
                'description' => 'Realiza una transferencia a nuestra cuenta bancaria.',
                'is_active' => true,
                'settings' => null,
                'instructions' => "Banco: BBVA\nCLABE: 012345678901234567\nTitular: Mincoli SA de CV\nReferencia: Tu número de pedido",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
