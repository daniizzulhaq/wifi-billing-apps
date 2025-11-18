@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi')
@section('header', 'Laporan Laba Rugi')

@section('content')
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-search"></i> Lihat Laporan
            </button>
            <a href="{{ route('admin.laporan.laba-rugi.pdf', ['bulan' => $bulan]) }}" target="_blank"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </form>
    </div>
</div>

{{-- ======== Ringkasan Utama ======== --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-green-500 text-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold mb-2">Total Pemasukan</h3>
        <p class="text-2xl font-bold">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
    </div>
    <div class="bg-red-500 text-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold mb-2">Total Pengeluaran</h3>
        <p class="text-2xl font-bold">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
    </div>
    <div class="bg-{{ $labaRugi >= 0 ? 'blue' : 'orange' }}-500 text-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold mb-2">{{ $labaRugi >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</h3>
        <p class="text-2xl font-bold">Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</p>
    </div>
</div>

{{-- ======== Tabel Detail Pemasukan ======== --}}
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-green-700"><i class="fas fa-arrow-down"></i> Rincian Pemasukan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">No</th>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">Tanggal</th>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">Keterangan</th>
                        <th class="px-4 py-2 border text-right text-sm font-medium text-gray-700">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasMasuk as $index => $item)
                        <tr class="hover:bg-green-50">
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $item->keterangan }}</td>
                            <td class="px-4 py-2 border text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">Tidak ada data pemasukan bulan ini</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-green-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-2 border text-right">Total Pemasukan</td>
                        <td class="px-4 py-2 border text-right">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- ======== Tabel Detail Pengeluaran ======== --}}
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-red-700"><i class="fas fa-arrow-up"></i> Rincian Pengeluaran</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-red-100">
                    <tr>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">No</th>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">Tanggal</th>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">Keterangan</th>
                        <th class="px-4 py-2 border text-right text-sm font-medium text-gray-700">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasKeluar as $index => $item)
                        <tr class="hover:bg-red-50">
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $item->keterangan }}</td>
                            <td class="px-4 py-2 border text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">Tidak ada data pengeluaran bulan ini</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-red-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-2 border text-right">Total Pengeluaran</td>
                        <td class="px-4 py-2 border text-right">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- ======== Ringkasan Saldo ======== --}}
@if($saldo)
<div class="bg-white rounded-lg shadow mb-6 p-6">
    <h3 class="text-lg font-semibold mb-4 text-blue-700"><i class="fas fa-wallet"></i> Ringkasan Saldo Bulan Ini</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
        <div class="p-4 border rounded-lg">
            <p class="text-gray-600 mb-1">Saldo Awal</p>
            <p class="text-lg font-semibold">Rp {{ number_format($saldo->saldo_awal, 0, ',', '.') }}</p>
        </div>
        <div class="p-4 border rounded-lg">
            <p class="text-gray-600 mb-1">Total Masuk</p>
            <p class="text-lg font-semibold text-green-600">Rp {{ number_format($saldo->total_masuk, 0, ',', '.') }}</p>
        </div>
        <div class="p-4 border rounded-lg">
            <p class="text-gray-600 mb-1">Total Keluar</p>
            <p class="text-lg font-semibold text-red-600">Rp {{ number_format($saldo->total_keluar, 0, ',', '.') }}</p>
        </div>
        <div class="p-4 border rounded-lg">
            <p class="text-gray-600 mb-1">Saldo Akhir</p>
            <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($saldo->saldo_akhir, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endif

{{-- ======== Ringkasan Akhir ======== --}}
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800"><i class="fas fa-chart-line"></i> Kesimpulan</h3>
    <p class="text-gray-700 leading-relaxed">
        Berdasarkan data transaksi keuangan bulan <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</strong>,
        total pemasukan yang tercatat sebesar <strong>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</strong>,
        sedangkan total pengeluaran mencapai <strong>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</strong>.
        Dengan demikian, hasil akhir menunjukkan
        <strong class="{{ $labaRugi >= 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $labaRugi >= 0 ? 'laba bersih' : 'rugi bersih' }}
        </strong>
        sebesar <strong>Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</strong>.
    </p>
</div>
@endsection
