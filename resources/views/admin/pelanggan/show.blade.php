@extends('layouts.admin')

@section('title', 'Detail Pelanggan')
@section('header', 'Detail Pelanggan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Informasi Pelanggan</h3>
        <div class="space-x-2">
            <a href="{{ route('admin.pelanggan.edit', $pelanggan) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.pelanggan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Data Pelanggan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-4 border-b pb-2">Data Pribadi</h4>
                
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                    <p class="text-gray-800 font-semibold">{{ $pelanggan->nama }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-800">{{ $pelanggan->user->email }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">No HP</label>
                    <p class="text-gray-800">{{ $pelanggan->no_hp }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Alamat</label>
                    <p class="text-gray-800">{{ $pelanggan->alamat ?? '-' }}</p>
                </div>
            </div>

            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Paket</h4>
                
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Paket WiFi</label>
                    <p class="text-gray-800 font-semibold">{{ $pelanggan->paket_wifi }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Harga Bulanan</label>
                    <p class="text-gray-800 text-xl font-bold text-blue-600">
                        Rp {{ number_format($pelanggan->harga_bulanan, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <p>
                        @if($pelanggan->status == 'aktif')
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 font-semibold">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 font-semibold">
                                <i class="fas fa-times-circle"></i> Nonaktif
                            </span>
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Tanggal Daftar</label>
                    <p class="text-gray-800">{{ $pelanggan->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Riwayat Tagihan -->
        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-700 mb-4 border-b pb-2">Riwayat Tagihan</h4>
            
            @if(isset($pelanggan->tagihans) && $pelanggan->tagihans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pelanggan->tagihans as $index => $tagihan)
                        <tr class="{{ $tagihan->status == 'belum_dibayar' && $tagihan->jatuh_tempo < now() ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tagihan->bulan->format('F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tagihan->denda > 0)
                                    <span class="text-red-600 font-semibold">Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $tagihan->jatuh_tempo->format('d/m/Y') }}
                                @if($tagihan->status == 'belum_dibayar' && $tagihan->jatuh_tempo < now())
                                    <span class="text-red-600 text-xs block">(Terlambat)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tagihan->status == 'lunas')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-semibold">
                                        <i class="fas fa-check"></i> Lunas
                                    </span>
                                @elseif($tagihan->status == 'belum_lunas')
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 font-semibold">
                                        <i class="fas fa-clock"></i> Belum Lunas
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 font-semibold">
                                        <i class="fas fa-exclamation-circle"></i> Belum Dibayar
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-file-invoice text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Belum ada riwayat tagihan</p>
            </div>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-8 flex justify-end space-x-3">
            <form action="{{ route('admin.pelanggan.destroy', $pelanggan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini? Data tidak dapat dikembalikan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                    <i class="fas fa-trash"></i> Hapus Pelanggan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection