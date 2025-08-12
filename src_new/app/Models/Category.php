<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',  // Nama kategori
    ];

    // Relasi ke tabel Kostum
     public function kostums()
    {
        return $this->hasMany(Kostum::class);
    }
}
