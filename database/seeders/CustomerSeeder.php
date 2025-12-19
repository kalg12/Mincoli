<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@example.com',
                'phone' => '5512345678',
                'status' => 'active',
                'orders' => [
                    ['total' => 1840.50, 'status' => 'paid', 'placed_at' => '-12 days'],
                    ['total' => 629.90, 'status' => 'delivered', 'placed_at' => '-35 days'],
                ],
                'notes' => ['Cliente VIP, responde rápido en WhatsApp', 'Prefiere entregas por la tarde'],
            ],
            [
                'name' => 'María Ruiz',
                'email' => 'maria.ruiz@example.com',
                'phone' => '5598765432',
                'status' => 'active',
                'orders' => [
                    ['total' => 2150.00, 'status' => 'paid', 'placed_at' => '-20 days'],
                    ['total' => 480.00, 'status' => 'pending', 'placed_at' => '-5 days'],
                ],
                'notes' => ['Solicitó factura CFDI', 'Aplicar descuento del 5% en próximas compras'],
            ],
            [
                'name' => 'Sandra Díaz',
                'email' => 'sandra.diaz@example.com',
                'phone' => '5545678901',
                'status' => 'inactive',
                'orders' => [
                    ['total' => 9820.00, 'status' => 'shipped', 'placed_at' => '-60 days'],
                ],
                'notes' => ['Reactivar con campaña de retargeting'],
            ],
            [
                'name' => 'Luis Fernández',
                'email' => 'luis.fernandez@example.com',
                'phone' => '5522334455',
                'status' => 'active',
                'orders' => [
                    ['total' => 350.00, 'status' => 'paid', 'placed_at' => '-2 days'],
                    ['total' => 1290.75, 'status' => 'delivered', 'placed_at' => '-15 days'],
                    ['total' => 220.00, 'status' => 'pending', 'placed_at' => '-1 day'],
                ],
                'notes' => ['Envía comprobantes por correo, no WhatsApp'],
            ],
            [
                'name' => 'Carmen Ortega',
                'email' => 'carmen.ortega@example.com',
                'phone' => '5588997744',
                'status' => 'blocked',
                'orders' => [
                    ['total' => 410.00, 'status' => 'refunded', 'placed_at' => '-90 days'],
                ],
                'notes' => ['Bloqueada por contracargo. Revisar antes de reactivar'],
            ],
        ];

        foreach ($customers as $data) {
            $customer = Customer::updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'status' => $data['status'],
                ]
            );

            foreach ($data['orders'] as $orderData) {
                Order::create([
                    'customer_id' => $customer->id,
                    'status' => $orderData['status'],
                    'channel' => 'web',
                    'subtotal' => $orderData['total'] * 0.84,
                    'iva_total' => $orderData['total'] * 0.16,
                    'shipping_cost' => 120,
                    'total' => $orderData['total'],
                    'placed_at' => Carbon::parse($orderData['placed_at']),
                ]);
            }

            foreach ($data['notes'] as $note) {
                CustomerNote::create([
                    'customer_id' => $customer->id,
                    'user_id' => null,
                    'note' => $note,
                ]);
            }
        }
    }
}
