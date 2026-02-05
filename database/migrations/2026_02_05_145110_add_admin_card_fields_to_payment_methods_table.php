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
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('card_number', 16)->nullable()->after('supports_card_number');
            $table->string('card_type', 20)->nullable()->after('card_number'); // credit, debit
            $table->string('card_holder_name')->nullable()->after('card_type');
            $table->string('bank_name')->nullable()->after('card_holder_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['card_number', 'card_type', 'card_holder_name', 'bank_name']);
        });
    }
};
