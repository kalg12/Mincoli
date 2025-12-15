<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_financings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('payment_plan_id')->constrained('payment_plans')->onDelete('cascade');
            $table->decimal('down_payment', 10, 2);
            $table->decimal('financed_amount', 10, 2);
            $table->date('start_date');
            $table->date('due_date');
            $table->enum('status', ['active', 'paid', 'late', 'cancelled'])->default('active');
            $table->timestamps();

            $table->index('order_id');
            $table->index('payment_plan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_financings');
    }
};
