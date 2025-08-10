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
            $table->decimal('total', 5, 2)->nullable();
            $table->decimal('paid', 5, 2)->nullable();
            $table->decimal('change', 5, 2)->nullable();
            $table->string('note', 100)->nullable(); //optional
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
