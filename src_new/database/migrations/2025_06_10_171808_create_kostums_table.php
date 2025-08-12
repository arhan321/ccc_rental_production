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
        Schema::create('kostums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');  // Relasi ke kategori
            $table->string('image');  // Gambar produk
            $table->string('nama_kostum');  // Nama produk
            $table->enum('ukuran', ['S', 'M', 'L', 'XL']);  // Ukuran produk
            $table->integer('harga_sewa');  // Harga sewa produk
            $table->integer('stok')->nullable();  // Stok produk yang tersedia
            $table->text('deskripsi')->nullable();  // Deskripsi produk
            $table->enum('status', ['Tersedia', 'Terbooking'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kostums');
    }
};
