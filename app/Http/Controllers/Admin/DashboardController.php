<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Saldo;
use App\Models\Kas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->format('Y-m');
        
        // Total pelanggan aktif
        $totalPelanggan = Pelanggan::aktif()->count();
        
        // Total tagihan bulan ini
        $totalTagihanBulanIni = Tagihan::where('bulan', $bulanIni)->sum('nominal');
        
        // Total pembayaran bulan ini
        $totalPembayaranBulanIni = Kas::masuk()
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->sum('jumlah');
        
        // Tagihan belum dibayar
        $tagihanBelumDibayar = Tagihan::belumDibayar()->count();
        
        // Saldo bulan ini
        $saldoBulanIni = Saldo::where('bulan', $bulanIni)->first();
        $saldoAkhir = $saldoBulanIni ? $saldoBulanIni->saldo_akhir : 0;
        
        // Tagihan jatuh tempo
        $tagihanJatuhTempo = Tagihan::jatuhTempo()->with('pelanggan')->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalPelanggan',
            'totalTagihanBulanIni',
            'totalPembayaranBulanIni',
            'tagihanBelumDibayar',
            'saldoAkhir',
            'tagihanJatuhTempo'
        ));
    }
}