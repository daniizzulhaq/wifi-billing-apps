<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kas::query();

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $date = Carbon::createFromFormat('Y-m', $request->bulan);
            $query->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);
        }

        $kas = $query->latest('tanggal')->paginate(15);
        
        // Hitung total
        $totalMasuk = Kas::masuk()->sum('jumlah');
        $totalKeluar = Kas::keluar()->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;
        
        return view('admin.kas.index', compact('kas', 'totalMasuk', 'totalKeluar', 'saldo'));
    }

    public function create()
    {
        return view('admin.kas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            Kas::create($validated);
            
            // Update saldo
            $this->updateSaldo($validated['tanggal'], $validated['jumlah'], $validated['jenis']);
            
            DB::commit();
            return redirect()->route('admin.kas.index')
                ->with('success', 'Transaksi kas berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function updateSaldo($tanggal, $jumlah, $jenis)
    {
        $bulan = Carbon::parse($tanggal)->format('Y-m');
        $bulanSebelumnya = Carbon::parse($tanggal)->subMonth()->format('Y-m');
        
        $saldo = Saldo::firstOrNew(['bulan' => $bulan]);
        
        if (!$saldo->exists) {
            $saldoSebelumnya = Saldo::where('bulan', $bulanSebelumnya)->first();
            $saldo->saldo_awal = $saldoSebelumnya ? $saldoSebelumnya->saldo_akhir : 0;
        }
        
        if ($jenis == 'masuk') {
            $saldo->total_masuk += $jumlah;
        } else {
            $saldo->total_keluar += $jumlah;
        }
        
        $saldo->saldo_akhir = $saldo->saldo_awal + $saldo->total_masuk - $saldo->total_keluar;
        $saldo->save();
    }

    public function destroy(Kas $ka)
    {
        try {
            // Hanya bisa hapus kas yang bukan dari pembayaran
            if ($ka->pembayaran_id) {
                return back()->with('error', 'Kas dari pembayaran tidak bisa dihapus!');
            }
            
            DB::beginTransaction();
            
            // Update saldo (kebalikan dari jenis)
            $bulan = $ka->tanggal->format('Y-m');
            $saldo = Saldo::where('bulan', $bulan)->first();
            
            if ($saldo) {
                if ($ka->jenis == 'masuk') {
                    $saldo->total_masuk -= $ka->jumlah;
                } else {
                    $saldo->total_keluar -= $ka->jumlah;
                }
                $saldo->saldo_akhir = $saldo->saldo_awal + $saldo->total_masuk - $saldo->total_keluar;
                $saldo->save();
            }
            
            $ka->delete();
            
            DB::commit();
            return back()->with('success', 'Transaksi kas berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}