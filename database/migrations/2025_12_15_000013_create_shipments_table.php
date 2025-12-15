<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('carrier');
            $table->string('tracking_number')->nullable();
            $table->enum('status', ['pending', 'shipped', 'in_transit', 'delivered', 'returned', 'cancelled'])->default('pending');
            $table->enum('zone_type', ['cdmx', 'edomex', 'republica', 'extendida'])->default('republica');
            $table->decimal('shipping_cost', 10, 2);
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('status');
            $table->index('zone_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
