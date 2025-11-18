<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_id',
        'tanggal_bayar',
        'jumlah',
        'metode',
        'bukti_transfer',
        'status_approval',
        'catatan_admin',
        'tanggal_approval',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'tanggal_approval' => 'datetime',
    ];

    // Relasi ke Tagihan
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    // Relasi ke Kas
    public function kas()
    {
        return $this->hasOne(Kas::class);
    }

    // Scope untuk pembayaran pending
    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    // Scope untuk pembayaran approved
    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
        ];

        return $badges[$this->status_approval] ?? '';
    }
}