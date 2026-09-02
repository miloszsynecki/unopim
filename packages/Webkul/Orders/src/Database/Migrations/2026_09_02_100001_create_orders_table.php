<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();

            // Channel identity. This is a READ MODEL: (channel, channel_order_number)
            // is unique so re-ingesting the same order updates rather than duplicates
            // — the order pipe stays idempotent on the source id (doc §8).
            $table->string('channel')->index();              // allegro | presta | ...
            $table->string('channel_order_number');          // e.g. 345631141
            $table->string('shop_order_number')->nullable(); // e.g. 1246
            $table->string('source')->nullable();            // dywania_pl / PRESTA
            $table->string('external_transaction_id')->nullable(); // Allegro txn uuid

            $table->string('status')->default('new')->index();

            // Buyer.
            $table->string('customer_name')->nullable();
            $table->string('customer_login')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            // Money.
            $table->string('currency', 3)->default('PLN');
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('paid', 12, 2)->default(0);
            $table->string('payment_method')->nullable();

            // Shipping. `smart` drives routing FIRST, carrier second (doc §6).
            $table->string('delivery_method')->nullable();
            $table->decimal('delivery_cost', 12, 2)->nullable();
            $table->boolean('cod')->default(false);          // pobranie
            $table->boolean('smart')->default(false);        // Allegro Smart!
            $table->json('delivery_address')->nullable();
            $table->json('invoice_address')->nullable();
            $table->json('pickup_point')->nullable();

            // Link back to the source of truth (Symfonia), never authored here.
            $table->string('symfonia_document_number')->nullable();
            $table->string('symfonia_document_id')->nullable();

            $table->timestamp('ordered_at')->nullable()->index();
            $table->timestamp('status_changed_at')->nullable();
            $table->timestamps();

            $table->unique(['channel', 'channel_order_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
