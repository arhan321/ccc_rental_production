<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kostum extends Model
{
    use HasFactory;

    protected $table = 'kostums';

    protected $fillable = [
        'category_id', 'image', 'nama_kostum', 'ukuran', 'harga_sewa', 'stok', 'deskripsi', 'status',
    ];

    // Relasi ke tabel ProductSchedule
    public function schedules()
    {
        return $this->hasMany(ProductSchedule::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);  // Relasi dengan Category
    }

    // Relasi ke tabel OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
