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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transfer_number')->nullable()->after('card_holder_name')->comment('Bank transfer transaction number or folio');
            $table->string('capture_line')->nullable()->after('transfer_number')->comment('Payment capture line / reference for transfers');
        });

        Schema::table('pos_payments', function (Blueprint $table) {
            $table->string('transfer_number')->nullable()->after('card_holder_name')->comment('Bank transfer transaction number or folio');
            $table->string('capture_line')->nullable()->after('transfer_number')->comment('Payment capture line / reference for transfers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['transfer_number', 'capture_line']);
        });

        Schema::table('pos_payments', function (Blueprint $table) {
            $table->dropColumn(['transfer_number', 'capture_line']);
        });
    }
};

