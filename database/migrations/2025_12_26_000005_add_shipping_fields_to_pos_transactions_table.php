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
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->string('shipping_contact_phone', 30)->nullable()->after('payment_status');
            $table->text('shipping_address')->nullable()->after('shipping_contact_phone');
            $table->text('shipping_notes')->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->dropColumn(['shipping_contact_phone', 'shipping_address', 'shipping_notes']);
        });
    }
};
