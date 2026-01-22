<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReleaseExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:release-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Libera el stock apartado de pedidos pendientes que han expirado.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = \App\Models\Order::expired()->with('items')->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('No hay reservaciones expiradas.');
            return;
        }

        foreach ($expiredOrders as $order) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                // Return stock
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        \App\Models\ProductVariant::where('id', $item->variant_id)->increment('stock', $item->quantity);
                    } else {
                        \App\Models\Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }

                // Update status
                $order->update([
                    'status' => 'cancelled',
                    'expires_at' => null
                ]);

                \App\Models\OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => 'pending',
                    'to_status' => 'cancelled',
                    'note' => 'Reserva liberada automáticamente (Expiración de tiempo)',
                ]);
            });

            $this->info("Pedido #{$order->order_number} liberado.");
        }

        $this->info('Proceso de liberación completado.');
    }
}
