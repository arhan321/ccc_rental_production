<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kostums_id', 'order_id','tanggal_mulai', 'tanggal_selesai',
    ];

    public function order()
{
    return $this->belongsTo(\App\Models\Order::class);
}

    // Relasi ke tabel Kostum
    public function kostums()       // ganti jadi tunggal → kostum()
    {
        return $this->belongsTo(Kostum::class, 'kostums_id');
    }
}
