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
        Schema::create('recapitulation_item', function (Blueprint $table) {
            $table->foreignId('recapitulation_id')->constrained('recapitulations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('salesman_id')->constrained('salesmen')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('total_quantity');
            $table->decimal('total_price', 25, 0)->default(0);
            $table->decimal('total_discount', 25, 0)->default(0);
            $table->decimal('total_net_price', 25, 0)->default(0);
            $table->primary(['recapitulation_id', 'item_id', 'salesman_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recapitulation_item');
    }
};
