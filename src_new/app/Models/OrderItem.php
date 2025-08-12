<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'kostums_id', 'ukuran', 'jumlah', 'harga_sewa',
    ];

    // Relasi ke tabel Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    

    // Relasi ke tabel Kostum
    public function kostums()
    {
        return $this->belongsTo(Kostum::class, 'kostums_id');
    }

    protected static function booted()
{
    static::saving(function ($orderItem) {
        // Kalau harga_sewa belum diset, ambil dari harga kostum
        if (empty($orderItem->harga_sewa) && $orderItem->kostums_id) {
            $kostum = \App\Models\Kostum::find($orderItem->kostums_id);
            if ($kostum) {
                $orderItem->harga_sewa = $kostum->harga;
            }
        }
    });
}

}
