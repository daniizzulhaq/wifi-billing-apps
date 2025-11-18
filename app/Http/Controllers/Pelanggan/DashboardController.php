<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil user yang login
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        // Siapkan data yang aman (null-safe)
        $statusPaket = $pelanggan?->status ?? 'nonaktif';
        $paketWifi = $pelanggan?->paket_wifi ?? '-';
        $hargaBulanan = $pelanggan?->harga_bulanan ?? 0;
        $tagihanBelumLunas = $pelanggan?->tagihans?->where('status', 'belum_lunas')->count() ?? 0;
        $riwayatTagihan = $pelanggan?->tagihans?->sortByDesc('bulan_tagihan')->take(5) ?? collect();

        return view('pelanggan.dashboard', compact(
            'user',
            'pelanggan',
            'statusPaket',
            'paketWifi',
            'hargaBulanan',
            'tagihanBelumLunas',
            'riwayatTagihan'
        ));
    }

    public function tagihan()
    {
        $pelanggan = Auth::user()->pelanggan;

        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $tagihans = $pelanggan->tagihans()
            ->orderBy('bulan_tagihan', 'desc')
            ->paginate(10);

        return view('pelanggan.tagihan.index', compact('tagihans', 'pelanggan'));
    }
}
