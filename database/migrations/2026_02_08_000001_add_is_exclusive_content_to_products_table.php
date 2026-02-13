<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_exclusive_content')->default(false)->after('is_featured');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_exclusive_content');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_exclusive_content']);
            $table->dropColumn('is_exclusive_content');
        });
    }
};
