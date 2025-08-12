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
        Schema::create('product_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kostums_id')->constrained()->onDelete('cascade');  // Relasi ke kostums
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade'); // Relasi ke orders
            $table->date('tanggal_mulai');  // Tanggal mulai sewa produk
            $table->date('tanggal_selesai');  // Tanggal selesai sewa produk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_schedules');
    }
};
