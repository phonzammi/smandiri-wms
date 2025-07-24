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
        Schema::create('recapitulations', function (Blueprint $table) {
            $table->id();
            $table->string('recapitulation_code', 50)->unique();
            $table->date('date');
            $table->string('driver', 50);
            $table->string('helper', 50);
            $table->string('license_plate', 50);
            $table->integer('outgoing_transaction_count');
            $table->integer('salesman_count');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recapitulations');
    }
};
