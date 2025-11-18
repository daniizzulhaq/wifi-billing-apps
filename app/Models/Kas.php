<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    protected $fillable = [
        'jenis',
        'tanggal',
        'jumlah',
        'kategori',
        'keterangan',
        'pembayaran_id',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
    ];

    // Relasi ke Pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    // Scope untuk kas masuk
    public function scopeMasuk($query)
    {
        return $query->where('jenis', 'masuk');
    }

    // Scope untuk kas keluar
    public function scopeKeluar($query)
    {
        return $query->where('jenis', 'keluar');
    }
}