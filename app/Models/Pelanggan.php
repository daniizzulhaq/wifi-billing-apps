<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'no_hp',
        'paket_wifi',
        'harga_bulanan',
        'status',
    ];

    protected $casts = [
        'harga_bulanan' => 'decimal:2',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tagihan - PERBAIKAN: hasMany dan nama plural
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }
    
    // Scope untuk pelanggan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}