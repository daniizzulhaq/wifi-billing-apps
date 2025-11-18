<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with('pelanggan');

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('bulan', Carbon::parse($request->bulan)->month)
                  ->whereYear('bulan', Carbon::parse($request->bulan)->year);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihans = $query->latest('bulan')->paginate(15);

        return view('admin.tagihan.index', compact('tagihans'));
    }

    public function generate(Request $request)
    {
        // Validasi input
        $request->validate([
            'bulan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:bulan'
        ], [
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan bulan tagihan'
        ]);

        $bulan = $request->input('bulan', Carbon::now()->format('Y-m-01'));
        $jatuhTempo = $request->input('tanggal_jatuh_tempo');

        // Cek apakah tagihan bulan ini sudah dibuat
        $existingCount = Tagihan::whereMonth('bulan', Carbon::parse($bulan)->month)
                                ->whereYear('bulan', Carbon::parse($bulan)->year)
                                ->count();

        if ($existingCount > 0) {
            return back()->with('error', 'Tagihan untuk bulan ini sudah dibuat!');
        }

        try {
            // Ambil semua pelanggan aktif
            $pelanggans = Pelanggan::aktif()->get();

            foreach ($pelanggans as $pelanggan) {
                Tagihan::create([
                    'pelanggan_id'   => $pelanggan->id,
                    'bulan'          => $bulan,
                    'jumlah_tagihan' => $pelanggan->harga_bulanan,
                    'nominal'        => $pelanggan->harga_bulanan,
                    'jatuh_tempo'    => $jatuhTempo, // ✅ Menggunakan tanggal yang dipilih manual
                    'status'         => 'belum_lunas',
                    'denda'          => 0,
                ]);
            }

            return back()->with(
                'success',
                "Berhasil membuat {$pelanggans->count()} tagihan untuk bulan " . Carbon::parse($bulan)->format('F Y') . 
                " dengan jatuh tempo " . Carbon::parse($jatuhTempo)->format('d F Y')
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tunggakan()
    {
        $tagihans = Tagihan::where('status', 'belum_lunas')
            ->with('pelanggan')
            ->orderBy('jatuh_tempo')
            ->paginate(15);

        return view('admin.tagihan.tunggakan', compact('tagihans'));
    }
}