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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('comic_id')->constrained('comic')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('IDR');
            $table->string('payment_type')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed, expired, cancelled
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'comic_id']);
            $table->index('order_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
