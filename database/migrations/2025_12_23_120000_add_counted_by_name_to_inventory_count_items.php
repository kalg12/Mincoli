<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_count_items', function (Blueprint $table) {
            $table->string('counted_by_name')->nullable()->after('counted_by');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_count_items', function (Blueprint $table) {
            $table->dropColumn('counted_by_name');
        });
    }
};
