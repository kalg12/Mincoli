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
        Schema::table('pos_payments', function (Blueprint $table) {
            $table->string('card_number')->nullable()->after('reference')->comment('16 digit card number for credit/debit cards');
            $table->string('card_type')->nullable()->after('card_number')->comment('Type of card: credit, debit');
            $table->string('card_holder_name')->nullable()->after('card_type')->comment('Name of the card holder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_payments', function (Blueprint $table) {
            $table->dropColumn(['card_number', 'card_type', 'card_holder_name']);
        });
    }
};
