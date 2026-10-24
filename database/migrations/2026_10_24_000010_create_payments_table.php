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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->enum('method', ['cod', 'vnpay']);
            $table->unsignedInteger('amount');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('txn_ref')->unique()->nullable();
            $table->string('gateway_txn_no')->nullable();
            $table->string('bank_code', 50)->nullable();
            $table->string('response_code', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
