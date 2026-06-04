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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('menu_variant_id')->nullable()->constrained()->nullOnDelete();

            $table->string('item_name');
            $table->string('variant_name')->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('menu_item_id');
            $table->index('menu_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};