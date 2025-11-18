<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['tagihan.pelanggan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function pending()
    {
        $pembayarans = Pembayaran::with(['tagihan.pelanggan'])
            ->where('status_approval', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pembayaran.pending', compact('pembayarans'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.pelanggan']);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function approve(Request $request, Pembayaran $pembayaran)
    {
        // Validasi jika pembayaran sudah diverifikasi
        if ($pembayaran->status_approval !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pembayaran sudah diverifikasi sebelumnya');
        }

        try {
            DB::beginTransaction();

            // ✅ Update pembayaran - SESUAI MODEL
            $pembayaran->update([
                'status_approval' => 'approved',
                'tanggal_approval' => now(), // ✅ Field yang benar
                'catatan_admin' => $request->catatan_admin, // ✅ Field yang benar
            ]);

            // Update status tagihan menjadi lunas
            $pembayaran->tagihan->update([
                'status' => 'lunas'
            ]);

            // Catat ke kas (pemasukan)
            Kas::create([
                'tanggal' => now(),
                'jenis' => 'masuk', 
                'kategori' => 'pembayaran_pelanggan',
                'jumlah' => $pembayaran->jumlah,
                'keterangan' => 'Pembayaran tagihan ' . 
                    $pembayaran->tagihan->pelanggan->nama . ' - ' . 
                    \Carbon\Carbon::parse($pembayaran->tagihan->bulan)->format('F Y'),
                'pelanggan_id' => $pembayaran->tagihan->pelanggan_id
            ]);

            DB::commit();

            return redirect()->route('admin.pembayaran.pending')
                ->with('success', 'Pembayaran berhasil disetujui dan dicatat ke kas');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyetujui pembayaran: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Pembayaran $pembayaran)
    {
        // ✅ Validasi field yang benar - SESUAI MODEL
        $request->validate([
            'catatan_admin' => 'required|string|min:10|max:500'
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi',
            'catatan_admin.min' => 'Alasan penolakan minimal 10 karakter',
            'catatan_admin.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        // Validasi jika pembayaran sudah diverifikasi
        if ($pembayaran->status_approval !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pembayaran sudah diverifikasi sebelumnya');
        }

        try {
            // ✅ Update pembayaran - SESUAI MODEL
            $pembayaran->update([
                'status_approval' => 'rejected',
                'tanggal_approval' => now(), // ✅ Field yang benar
                'catatan_admin' => $request->catatan_admin, // ✅ Field yang benar
            ]);

            return redirect()->route('admin.pembayaran.pending')
                ->with('success', 'Pembayaran berhasil ditolak. Pelanggan akan diberitahu melalui sistem.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    // Method tambahan untuk statistik (opsional)
    public function statistics()
    {
        $stats = [
            'total_pending' => Pembayaran::pending()->count(),
            'total_approved' => Pembayaran::approved()->count(),
            'total_rejected' => Pembayaran::where('status_approval', 'rejected')->count(),
            'total_amount_pending' => Pembayaran::pending()->sum('jumlah'),
            'total_amount_approved' => Pembayaran::approved()->sum('jumlah'),
        ];

        return response()->json($stats);
    }
}