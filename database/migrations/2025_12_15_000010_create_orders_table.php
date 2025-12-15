<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->enum('status', ['draft', 'pending', 'paid', 'partially_paid', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('draft');
            $table->enum('channel', ['web', 'live'])->default('web');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('iva_total', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->dateTime('placed_at')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('order_number');
            $table->index('status');
            $table->index('channel');
            $table->index('placed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
