<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_purchase_guides', function (Blueprint $table) {
            $table->id();
            $table->string('video_url')->nullable();
            $table->text('text');
            $table->string('whatsapp_url')->nullable();
            $table->string('cart_url')->nullable();
            $table->string('offers_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_purchase_guides');
    }
};
