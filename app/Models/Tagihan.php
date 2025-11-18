<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'bulan',
        'jumlah_tagihan',
        'jatuh_tempo',
        'status',
        'denda',
        'nominal',
    ];

    protected $casts = [
        'bulan' => 'date',
        'jatuh_tempo' => 'date',
        'jumlah_tagihan' => 'decimal:2',
        'denda' => 'decimal:2',
        'nominal' => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    // ✅ Tambahan
    public function scopeBelumDibayar($query)
    {
        return $query->where('status', 'belum_dibayar');
    }

    public function scopeJatuhTempo($query)
    {
        return $query->where('status', 'belum_dibayar')
                     ->whereDate('jatuh_tempo', '<', now());
    }
}
