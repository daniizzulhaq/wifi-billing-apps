@extends('layouts.admin')

@section('title', 'Buku Kas')
@section('header', 'Buku Kas')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-green-500 text-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold mb-2">Total Kas Masuk</h3>
        <p class="text-2xl font-bold">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
    </div>
    <div class="bg-red-500 text-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold mb-2">Total Kas Keluar</h3>
        <p class="text-2xl font-bold">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
    </div>
    <div class="bg-blue-500 text-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold mb-2">Saldo</h3>
        <p class="text-2xl font-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Transaksi Kas</h3>
        <a href="{{ route('admin.kas.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
            <i class="fas fa-plus"></i> Tambah Transaksi
        </a>
    </div>
    
    <div class="p-6">
        <div class="flex gap-4 mb-4">
            <form method="GET" class="flex gap-2">
                <select name="jenis" class="px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Jenis</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Kas Masuk</option>
                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Kas Keluar</option>
                </select>
                <input type="month" name="bulan" value="{{ request('bulan') }}" 
                       class="px-3 py-2 border border-gray-300 rounded-md">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kas as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->tanggal->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->jenis == 'masuk')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Masuk</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Keluar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->kategori }}</td>
                        <td class="px-6 py-4 whitespace-nowrap {{ $item->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $item->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">{{ $item->keterangan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$item->pembayaran_id)
                            <form action="{{ route('admin.kas.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400">Auto</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $kas->links() }}
        </div>
    </div>
</div>
@endsection