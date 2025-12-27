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
        Schema::create('pos_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_transaction_id')->constrained('pos_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('restrict');

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('iva_rate', 5, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('iva_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Item status: reserved (apartado), pending_shipment, shipped, completed, cancelled
            $table->enum('status', ['reserved', 'pending_shipment', 'shipped', 'completed', 'cancelled'])->default('reserved');

            $table->timestamps();

            $table->index(['pos_transaction_id', 'status']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_transaction_items');
    }
};
