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
        Schema::create('outgoing_transaction_item', function (Blueprint $table) {
            $table->foreignId('outgoing_transaction_id')->constrained('outgoing_transactions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 25);
            $table->decimal('discount', 25)->nullable()->default(0);
            $table->decimal('total_price', 25);
            $table->decimal('total_net_price', 25);
            $table->integer('returned')->nullable()->default(0);
            $table->primary(['outgoing_transaction_id', 'item_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_transaction_item');
    }
};
