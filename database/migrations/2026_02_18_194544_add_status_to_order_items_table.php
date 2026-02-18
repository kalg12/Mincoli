<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('status', [
                'pending',        // Pendiente por entregar/enviar
                'preparing',      // En preparación
                'ready_to_ship',  // Listo para enviar
                'shipped',        // Enviado
                'delivered',      // Entregado
                'cancelled'       // Cancelado
            ])->default('pending')->after('total');
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
