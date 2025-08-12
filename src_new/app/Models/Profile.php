<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'users_id', 'nama_lengkap', 'nomor_telepon', 'jenis_kelamin', 'tanggal_lahir', 'avatar_url', 'instagram',
    ];

    // Relasi ke tabel User
    public function user()
{
    return $this->belongsTo(User::class, 'users_id');
}

    // Relasi ke tabel Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customRequests()
{
    return $this->hasMany(CustomRequest::class, 'profile_id');
}
}
