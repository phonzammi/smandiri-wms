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
        Schema::create('outgoing_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code', 50)->unique();
            $table->date('date');
            $table->foreignId('salesman_id')->constrained('salesmen')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnUpdate()->cascadeOnDelete();
            $table->bigInteger('quantities')->default(0);
            $table->decimal('prices', 25, 0)->default(0);
            $table->decimal('discounts', 25, 0)->default(0);
            $table->decimal('net_prices', 25, 0)->default(0);
            $table->string('status', 20)->default('draft');
            $table->string('cancel_reason')->nullable()->default(NULL);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_transactions');
    }
};
