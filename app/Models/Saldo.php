<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulan',
        'saldo_awal',
        'total_masuk',
        'total_keluar',
        'saldo_akhir',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
        'total_masuk' => 'decimal:2',
        'total_keluar' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    // Accessor untuk format bulan
    public function getBulanFormatAttribute()
    {
        return Carbon::createFromFormat('Y-m', $this->bulan)->translatedFormat('F Y');
    }
}