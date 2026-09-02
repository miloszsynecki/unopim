<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->unsignedBigInteger('product_id')->nullable(); // our internal product, when matched
            $table->string('external_product_id')->nullable();    // channel product id, e.g. 369546119
            $table->string('sku')->nullable();
            $table->string('ean')->nullable();
            $table->string('name');

            $table->integer('qty')->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('weight', 10, 3)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
