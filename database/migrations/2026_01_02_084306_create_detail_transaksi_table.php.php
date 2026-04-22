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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_transaksi')
                ->constrained('transaksi')
                ->cascadeOnDelete();

            $table->foreignId('id_produk')
                ->constrained('produk')
                ->cascadeOnDelete();

            $table->integer('quantity');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('detail_transaksi');
    }
};
