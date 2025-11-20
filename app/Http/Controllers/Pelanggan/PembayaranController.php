<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // Pakai Route Model Binding: {tagihan}
    public function create(Tagihan $tagihan)
    {
        // Cek tagihan milik pelanggan login
        if ((int)$tagihan->pelanggan_id !== (int)Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Cek status tagihan
        if ($tagihan->status === 'lunas') {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('warning', 'Tagihan ini sudah lunas.');
        }

        // Cek pembayaran pending
        $pembayaranPending = Pembayaran::where('tagihan_id', $tagihan->id)
            ->where('status_approval', 'pending')
            ->first();

        if ($pembayaranPending) {
            return redirect()->route('pelanggan.pembayaran.index')
                ->with('warning', 'Anda sudah mengajukan pembayaran untuk tagihan ini. Menunggu verifikasi admin.');
        }

        return view('pelanggan.pembayaran.create', compact('tagihan'));
    }

    public function store(Request $request, Tagihan $tagihan)
    {
        // Cek kepemilikan
        if ($tagihan->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi
        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Jika tagihan lunas
        if ($tagihan->status === 'lunas') {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('error', 'Tagihan ini sudah lunas.');
        }

        // Upload bukti transfer
        $buktiPath = $request->file('bukti_transfer')->store(
            'bukti_transfer',
            'public'
        );

        // Hitung total bayar
        $jumlah = $tagihan->jumlah_tagihan + $tagihan->denda;

        // Buat pembayaran
        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'jumlah' => $jumlah,
            'metode_pembayaran' => 'transfer',
            'bukti_transfer' => $buktiPath,
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('pelanggan.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diajukan. Menunggu verifikasi admin.');
    }

    public function show(Pembayaran $pembayaran)
    {
        // Cek kepemilikan pembayaran
        if ($pembayaran->tagihan->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403);
        }

        $pembayaran->load('tagihan');
        return view('pelanggan.pembayaran.show', compact('pembayaran'));
    }
}
