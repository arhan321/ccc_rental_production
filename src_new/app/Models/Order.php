<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id', 'kostums_id','nomor_pesanan', 'tanggal_order', 'tanggal_batas_sewa', 'durasi_sewa', 'total_harga', 'status', 'alamat_toko',
    ];

    // Relasi ke tabel Profile
    public function profile()
{
    return $this->belongsTo(Profile::class); // otomatis cari profile_id
}

protected $casts = [
        'tanggal_order'       => 'datetime',
        'tanggal_batas_sewa'  => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    // Relasi ke tabel OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke tabel Return (Pengembalian)
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function productSchedule()
{
    return $this->hasOne(ProductSchedule::class);
}

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pesanan) {
            // Nomor pesanan otomatis
            if (empty($pesanan->nomor_pesanan)) {
                $lastId = self::max('id') ?? 0;
                $nextId = $lastId + 1;
                $pesanan->nomor_pesanan = 'PSN-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
