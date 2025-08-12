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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Relasi ke tabel orders
            $table->foreignId('kostums_id')->constrained()->onDelete('cascade');  // Relasi ke tabel kostums
            $table->enum('ukuran', ['S', 'M', 'L', 'Xl']);  // Ukuran produk yang dipesan
            $table->unsignedInteger('jumlah')->default(1)->change();  // Jumlah produk yang dipesan
            $table->integer('harga_sewa');  // Harga sewa per produk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
