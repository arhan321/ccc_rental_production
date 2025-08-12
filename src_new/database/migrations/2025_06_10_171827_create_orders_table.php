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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');  
            $table->string('nomor_pesanan');
            $table->date('tanggal_order');  // Tanggal pemesanan
            $table->date('tanggal_batas_sewa');  // Tanggal batas sewa produk
            $table->integer('durasi_sewa');  // Durasi sewa dalam hari (3 hari minimal)
            $table->integer('total_harga');  // Total harga sewa
            $table->enum('status', ['Menunggu','Diproses', 'Siap Di Ambil', 'Selesai'])->default('Menunggu');  // Status pesanan
            $table->text('alamat_toko');  // Alamat toko untuk pengambilan produk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
