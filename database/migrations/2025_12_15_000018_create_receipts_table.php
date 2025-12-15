<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('file_url');
            $table->enum('type', ['pdf', 'image']);
            $table->timestamp('created_at')->useCurrent();

            $table->index('payment_id');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
