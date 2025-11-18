<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Saldo;
use App\Models\Kas;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class LaporanController extends Controller
{
    public function labaRugi(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        
        // Data kas bulan dipilih
        $kasMasuk = Kas::masuk()
            ->whereYear('tanggal', Carbon::createFromFormat('Y-m', $bulan)->year)
            ->whereMonth('tanggal', Carbon::createFromFormat('Y-m', $bulan)->month)
            ->get();
        
        $kasKeluar = Kas::keluar()
            ->whereYear('tanggal', Carbon::createFromFormat('Y-m', $bulan)->year)
            ->whereMonth('tanggal', Carbon::createFromFormat('Y-m', $bulan)->month)
            ->get();
        
        $totalMasuk = $kasMasuk->sum('jumlah');
        $totalKeluar = $kasKeluar->sum('jumlah');
        $labaRugi = $totalMasuk - $totalKeluar;
        
        // Saldo
        $saldo = Saldo::where('bulan', $bulan)->first();
        
        return view('admin.laporan.laba-rugi', compact(
            'bulan',
            'kasMasuk',
            'kasKeluar',
            'totalMasuk',
            'totalKeluar',
            'labaRugi',
            'saldo'
        ));
    }

    public function saldo(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        
        $saldos = Saldo::whereYear('created_at', $tahun)
            ->orderBy('bulan')
            ->get();
        
        return view('admin.laporan.saldo', compact('saldos', 'tahun'));
    }

    public function exportLabaRugiPdf(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        
        $kasMasuk = Kas::masuk()
            ->whereYear('tanggal', Carbon::createFromFormat('Y-m', $bulan)->year)
            ->whereMonth('tanggal', Carbon::createFromFormat('Y-m', $bulan)->month)
            ->get();
        
        $kasKeluar = Kas::keluar()
            ->whereYear('tanggal', Carbon::createFromFormat('Y-m', $bulan)->year)
            ->whereMonth('tanggal', Carbon::createFromFormat('Y-m', $bulan)->month)
            ->get();
        
        $totalMasuk = $kasMasuk->sum('jumlah');
        $totalKeluar = $kasKeluar->sum('jumlah');
        $labaRugi = $totalMasuk - $totalKeluar;
        $saldo = Saldo::where('bulan', $bulan)->first();
        
        $pdf = PDF::loadView('admin.laporan.pdf.laba-rugi', compact(
            'bulan',
            'kasMasuk',
            'kasKeluar',
            'totalMasuk',
            'totalKeluar',
            'labaRugi',
            'saldo'
        ));
        
        return $pdf->download('laporan-laba-rugi-' . $bulan . '.pdf');
    }

    public function exportTunggakanPdf()
    {
        $tagihans = Tagihan::belumDibayar()
            ->with('pelanggan')
            ->orderBy('jatuh_tempo')
            ->get();
        
        $totalTunggakan = $tagihans->sum('nominal');
        
        $pdf = PDF::loadView('admin.laporan.pdf.tunggakan', compact('tagihans', 'totalTunggakan'));
        
        return $pdf->download('laporan-tunggakan-' . date('Y-m-d') . '.pdf');
    }
}