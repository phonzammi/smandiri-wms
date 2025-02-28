<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incoming_transaction_item', function (Blueprint $table) {
            $table->foreignId('incoming_transaction_id')->constrained('incoming_transactions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('total_quantity');
            $table->decimal('total_price', 25, 0);
            $table->primary(['incoming_transaction_id', 'item_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_transaction_item');
    }
};
