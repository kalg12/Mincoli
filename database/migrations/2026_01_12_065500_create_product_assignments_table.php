<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // Price to sell/collect
            $table->decimal('iva_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2); // Qty * Price + IVA?
            $table->decimal('amount_collected', 10, 2)->default(0); // What employee has paid back
            $table->enum('status', ['assigned', 'returned', 'completed'])->default('assigned');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_assignments');
    }
};
