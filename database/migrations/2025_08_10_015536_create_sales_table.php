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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // kasir yang akan melakukan transaksi
            $table->string('currency', 100)->nullable();
            $table->string('status', 100)->nullable()->default('input');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('user_id'); // untuk memfilter penjualan per kasir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
