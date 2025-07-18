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
        Schema::table('outgoing_transactions', function (Blueprint $table) {
            $table->after('cancel_reason', function (Blueprint $table) {
                $table->foreignId('recapitulation_id')->nullable()->constrained('recapitulations')->cascadeOnUpdate()->nullOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recapitulation_id');
        });
    }
};
