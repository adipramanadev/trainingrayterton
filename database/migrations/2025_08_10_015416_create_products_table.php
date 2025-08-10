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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('name', 100)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); //format defaultnya laravel
            $table->decimal('price', 5, 2)->nullable();
            $table->unsignedInteger('stock')->nullable()->default(0);
            $table->timestamps();

            $table->index('category_id'); //untuk memfilter product per category
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
