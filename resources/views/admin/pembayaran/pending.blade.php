@extends('layouts.admin')

@section('title', 'Pembayaran Pending')
@section('header', 'Pembayaran Pending')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Pembayaran Pending</h1>
            <p class="text-sm text-gray-600">Kelola dan verifikasi pembayaran yang menunggu persetujuan</p>
        </div>
        <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition inline-flex items-center">
            <i class="fas fa-list mr-2"></i> Semua Pembayaran
        </a>
    </div>
</div>

{{-- Alert Warning --}}
@if($pembayarans->total() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-yellow-900">Perhatian! Ada Pembayaran Menunggu</h3>
                <p class="text-sm text-yellow-800 mt-1">
                    Terdapat <strong class="font-bold">{{ $pembayarans->total() }}</strong> pembayaran yang memerlukan verifikasi Anda.
                    <a href="#tableSection" class="underline hover:text-yellow-900">Lihat daftar di bawah</a>
                </p>
            </div>
        </div>
    </div>
@endif

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pembayarans->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-blue-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Total Nominal</p>
                <p class="text-lg font-bold text-blue-600">
                    Rp {{ number_format($pembayarans->sum('jumlah'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Pelanggan Unik</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $pembayarans->pluck('tagihan.pelanggan.id')->unique()->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-lg shadow-sm" id="tableSection">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-file-invoice-dollar text-yellow-500"></i>
                Daftar Pembayaran Pending
            </h3>
            <span class="px-3 py-1 text-sm font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                {{ $pembayarans->total() }} Pending
            </span>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan Tagihan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pembayarans as $pembayaran)
                    <tr class="hover:bg-yellow-50 transition border-l-4 border-yellow-400">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">
                                {{ $pembayarans->firstItem() + $loop->index }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pembayaran->tagihan->pelanggan->nama }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-phone text-xs"></i> {{ $pembayaran->tagihan->pelanggan->no_hp }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                <i class="fas fa-calendar-check"></i>
                                {{ \Carbon\Carbon::parse($pembayaran->tagihan->bulan)->format('F Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-green-600">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-credit-card text-xs"></i> {{ ucfirst($pembayaran->metode ?? 'Transfer') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs transition inline-flex items-center"
                                   title="Verifikasi Pembayaran">
                                    <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                </a>
                                @if($pembayaran->bukti_transfer)
                                <a href="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" 
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded text-xs transition"
                                   target="_blank"
                                   title="Lihat Bukti">
                                    <i class="fas fa-image"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-check-circle text-green-500 text-5xl mb-4 opacity-50"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Semua Pembayaran Telah Diverifikasi</h3>
                                <p class="text-sm text-gray-500 mb-4">Tidak ada pembayaran yang menunggu verifikasi saat ini</p>
                                <a href="{{ route('admin.pembayaran.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm transition inline-flex items-center">
                                    <i class="fas fa-list mr-2"></i> Lihat Semua Pembayaran
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pembayarans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $pembayarans->firstItem() ?? 0 }}</span> - 
                    <span class="font-medium">{{ $pembayarans->lastItem() ?? 0 }}</span> dari 
                    <span class="font-medium">{{ $pembayarans->total() }}</span> pembayaran
                </div>
                <div>
                    {{ $pembayarans->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Tips Card --}}
@if($pembayarans->total() > 0)
<div class="mt-6 bg-white border-l-4 border-blue-400 rounded-lg shadow-sm p-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Tips Verifikasi Pembayaran</h3>
            <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                <li>Periksa kesesuaian nominal dengan tagihan</li>
                <li>Verifikasi kejelasan bukti transfer</li>
                <li>Pastikan tanggal pembayaran sesuai</li>
                <li>Berikan catatan yang jelas jika menolak pembayaran</li>
            </ul>
        </div>
    </div>
</div>
@endif

@endsection