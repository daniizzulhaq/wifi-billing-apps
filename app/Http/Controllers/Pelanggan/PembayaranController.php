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

    // Ubah parameter dari $tagihan_id menjadi Tagihan $tagihan (model binding)
    public function create($id)
{
    // Ambil tagihan milik pelanggan yang login
    $tagihan = Tagihan::where('id', $id)
        ->where('pelanggan_id', Auth::user()->pelanggan->id)
        ->firstOrFail();

    // Cek apakah tagihan sudah lunas
    if ($tagihan->status === 'lunas') {
        return redirect()->route('pelanggan.tagihan.index')
            ->with('warning', 'Tagihan ini sudah lunas.');
    }

    // Cek apakah sudah ada pembayaran pending untuk tagihan ini
    $pembayaranPending = Pembayaran::where('tagihan_id', $tagihan->id)
        ->where('status_approval', 'pending')
        ->first();

    if ($pembayaranPending) {
        return redirect()->route('pelanggan.pembayaran.index')
            ->with('warning', 'Anda sudah mengajukan pembayaran untuk tagihan ini. Menunggu verifikasi admin.');
    }

    return view('pelanggan.pembayaran.create', compact('tagihan'));
}


    // Ubah parameter dari Request $request menjadi Request $request, Tagihan $tagihan
    public function store(Request $request, Tagihan $tagihan)
    {
        // Pastikan tagihan milik pelanggan yang login
        if ($tagihan->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi
        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diupload',
            'bukti_transfer.image' => 'File harus berupa gambar',
            'bukti_transfer.max' => 'Ukuran file maksimal 2MB',
        ]);

        // Cek status tagihan
        if ($tagihan->status === 'lunas') {
            return redirect()->route('pelanggan.tagihan.index')
                ->with('error', 'Tagihan ini sudah lunas.');
        }

        // Upload bukti transfer
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = 'bukti_transfer_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti_transfer', $filename, 'public');
        }

        // Hitung total yang harus dibayar
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
            ->with('success', 'Pembayaran berhasil diajukan. Menunggu verifikasi dari admin.');
    }

    public function show(Pembayaran $pembayaran)
    {
        // Pastikan pembayaran milik pelanggan yang login
        if ($pembayaran->tagihan->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403);
        }

        $pembayaran->load('tagihan');
        return view('pelanggan.pembayaran.show', compact('pembayaran'));
    }

    
}