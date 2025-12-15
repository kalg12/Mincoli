<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_pixels', function (Blueprint $table) {
            $table->id();
            $table->enum('platform', ['meta', 'tiktok', 'other']);
            $table->string('pixel_id');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('platform');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_pixels');
    }
};
