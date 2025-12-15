<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_cut_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_cut_id')->constrained('weekly_cuts')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('sales_total', 12, 2);
            $table->decimal('costs_total', 12, 2);
            $table->decimal('iva_total', 12, 2);
            $table->decimal('net_profit', 12, 2);
            $table->integer('orders_count');

            $table->index('weekly_cut_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_cut_details');
    }
};
