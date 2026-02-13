<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exclusive_landing_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('contact_phone', 20)->nullable()->comment('Teléfono para solicitar acceso');
            $table->string('restricted_message', 500)->nullable();
            $table->string('expired_message', 500)->nullable();
            $table->boolean('show_filter_category')->default(true);
            $table->boolean('show_filter_type')->default(true);
            $table->boolean('show_filter_price')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exclusive_landing_configs');
    }
};
