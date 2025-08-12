<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telepon',
        'referensi',
        'ukuran',
        'template',
        'catatan',
        'status',
        'profile_id',
    ];

    // Relasi ke Profile
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
