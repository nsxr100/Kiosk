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

            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->string('method');
            $table->string('status')->default('pending');

            $table->decimal('amount', 10, 2);
            $table->decimal('cash_received', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();

            $table->string('provider')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('transaction_id')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('method');
            $table->index('status');
            $table->index('reference_number');
            $table->index('transaction_id');
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