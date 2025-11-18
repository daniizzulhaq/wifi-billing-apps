@extends('layouts.admin')

@section('title', 'Data Tagihan')
@section('header', 'Data Tagihan')

@section('content')
<!-- Form Generate Tagihan dengan Pilihan Tanggal Jatuh Tempo -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form action="{{ route('admin.tagihan.generate') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Input Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Bulan untuk Generate Tagihan <span class="text-red-500">*</span>
                    </label>
                    <input type="month" 
                           name="bulan" 
                           id="bulan"
                           value="{{ date('Y-m') }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('bulan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Tanggal Jatuh Tempo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal_jatuh_tempo" 
                           id="tanggal_jatuh_tempo"
                           value="{{ date('Y-m-d', strtotime('+1 month')) }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_jatuh_tempo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Contoh: Generate tgl 20 Nov → Jatuh tempo 20 Des</p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded transition">
                    <i class="fas fa-cog"></i> Generate Tagihan Bulanan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Tabel Daftar Tagihan -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Tagihan</h3>
        <a href="{{ route('admin.tagihan.tunggakan') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
            <i class="fas fa-exclamation-triangle"></i> Lihat Tunggakan
        </a>
    </div>
    
    <div class="p-6">
        <!-- Filter Form -->
        <div class="flex gap-4 mb-4">
            <form method="GET" class="flex gap-2">
                <input type="month" name="bulan" value="{{ request('bulan') }}" 
                       class="px-3 py-2 border border-gray-300 rounded-md">
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>

        <!-- Tabel Tagihan -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tagihans as $index => $tagihan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tagihans->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tagihan->pelanggan->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($tagihan->bulan)->format('F Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tagihan->denda > 0)
                                <span class="text-red-600">Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">
                            Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}
                            @if(\Carbon\Carbon::parse($tagihan->jatuh_tempo)->isPast() && $tagihan->status != 'lunas')
                                <span class="text-red-500 text-xs block">(Lewat tempo)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tagihan->status == 'lunas')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Lunas</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data tagihan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $tagihans->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-update tanggal jatuh tempo ketika bulan dipilih
    document.getElementById('bulan').addEventListener('change', function() {
        const bulanValue = this.value; // format: YYYY-MM
        if (bulanValue) {
            const [year, month] = bulanValue.split('-');
            // Set jatuh tempo ke bulan berikutnya dengan tanggal yang sama dengan hari ini
            const nextMonth = new Date(year, month, new Date().getDate());
            const jatuhTempo = nextMonth.toISOString().split('T')[0];
            document.getElementById('tanggal_jatuh_tempo').value = jatuhTempo;
        }
    });
</script>
@endpush
@endsection