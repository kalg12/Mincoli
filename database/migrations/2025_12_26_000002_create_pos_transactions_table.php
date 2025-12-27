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
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_session_id')->constrained('pos_sessions')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('transaction_number')->unique();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('iva_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Statuses for transaction lifecycle
            $table->enum('status', ['pending', 'reserved', 'completed', 'cancelled'])->default('pending');

            // Payment status
            $table->enum('payment_status', ['pending', 'partial', 'completed'])->default('pending');

            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();

            $table->index(['pos_session_id', 'status']);
            $table->index(['customer_id']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_transactions');
    }
};
