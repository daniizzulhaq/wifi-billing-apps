<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::whereHas('tagihan', function($query) {
                $query->where('pelanggan_id', Auth::user()->pelanggan->id);
            })
            ->with('tagihan')
            ->latest()
            ->paginate(10);

        return view('pelanggan.pembayaran.index', compact('pembayarans'));
    }

    public function create(Tagihan $tagihan)
    {
        $userPelangganId = Auth::user()->pelanggan->id ?? null;

        if (!$userPelangganId || $tagihan->pelanggan_id != $userPelangganId) {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('error', 'Anda tidak boleh mengakses tagihan ini.');
        }

        if ($tagihan->status === 'lunas') {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('warning', 'Tagihan ini sudah lunas.');
        }

        $pending = Pembayaran::where('tagihan_id', $tagihan->id)
            ->where('status_approval', 'pending')
            ->first();

        if ($pending) {
            return redirect()->route('pelanggan.pembayaran.index')
                ->with('warning', 'Anda sudah mengajukan pembayaran untuk tagihan ini.');
        }

        return view('pelanggan.pembayaran.create', compact('tagihan'));
    }

    public function store(Request $request, Tagihan $tagihan)
    {
        $userPelangganId = Auth::user()->pelanggan->id ?? null;

        if (!$userPelangganId || $tagihan->pelanggan_id != $userPelangganId) {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('error', 'Anda tidak boleh mengakses tagihan ini.');
        }

        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($tagihan->status === 'lunas') {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('error', 'Tagihan ini sudah lunas.');
        }

        $buktiPath = $request->file('bukti_transfer')->store(
            'bukti_transfer',
            'public'
        );

        $jumlah = $tagihan->jumlah_tagihan + $tagihan->denda;

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'jumlah' => $jumlah,
            'metode_pembayaran' => 'transfer',
            'bukti_transfer' => $buktiPath,
            'keterangan' => $validated['keterangan'] ?? null,
            'status_approval' => 'pending',
        ]);

        return redirect()->route('pelanggan.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diajukan. Menunggu verifikasi admin.');
    }

    public function show(Pembayaran $pembayaran)
    {
        $userPelangganId = Auth::user()->pelanggan->id ?? null;

        if (!$userPelangganId || $pembayaran->tagihan->pelanggan_id != $userPelangganId) {
            return redirect()->route('pelanggan.pembayaran.index')
                ->with('error', 'Anda tidak boleh melihat pembayaran ini.');
        }

        $pembayaran->load('tagihan');
        return view('pelanggan.pembayaran.show', compact('pembayaran'));
    }
}
