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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('products_id')
                ->constrained('products')
                ->onDelete('cascade');
            $table->foreignId('stores_id')
                ->constrained('stores')
                ->onDelete('cascade');
            $table->date('purchase_date');
            $table->double('lat', 10, 6);
            $table->double('lng', 10, 6);
            $table->integer('price');
            $table->foreignId('users_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
