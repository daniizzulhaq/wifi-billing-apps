@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')
@section('header', 'Daftar Pembayaran')

@section('content')
{{-- Header dengan Button Pending --}}
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Daftar Pembayaran</h1>
            <p class="text-sm text-gray-600">Kelola semua pembayaran pelanggan</p>
        </div>
        <div class="flex gap-2">
            @php
                $pendingCount = \App\Models\Pembayaran::where('status_approval', 'pending')->count();
            @endphp
            
            @if($pendingCount > 0)
                <a href="{{ route('admin.pembayaran.pending') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition inline-flex items-center relative">
                    <i class="fas fa-clock mr-2"></i> 
                    Pembayaran Pending
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $pendingCount }}
                    </span>
                </a>
            @else
                <a href="{{ route('admin.pembayaran.pending') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition inline-flex items-center">
                    <i class="fas fa-clock mr-2"></i> 
                    Pembayaran Pending
                </a>
            @endif
        </div>
    </div>
</div>

{{-- Alert Pending --}}
@if($pendingCount > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-yellow-900">Perhatian! Ada Pembayaran Menunggu</h3>
                <p class="text-sm text-yellow-800 mt-1">
                    Terdapat <strong class="font-bold">{{ $pendingCount }}</strong> pembayaran yang memerlukan verifikasi Anda.
                    <a href="{{ route('admin.pembayaran.pending') }}" class="underline hover:text-yellow-900 font-semibold">Klik di sini untuk verifikasi</a>
                </p>
            </div>
        </div>
    </div>
@endif

{{-- Filter & Search Section --}}
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Filter Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        {{-- Search --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Pelanggan</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Nama atau No HP pelanggan..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition flex-1">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Disetujui</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Pembayaran::where('status_approval', 'approved')->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Ditolak</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Pembayaran::where('status_approval', 'rejected')->count() }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-list text-blue-600 text-2xl"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-xs text-gray-600 uppercase tracking-wider">Total</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pembayarans->total() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-file-invoice-dollar text-blue-500"></i>
                Daftar Pembayaran
            </h3>
            @if(request()->has('status') || request()->has('search'))
                <span class="text-sm text-gray-600">
                    Menampilkan hasil filter
                </span>
            @endif
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
                    <tr class="hover:bg-gray-50 transition {{ $pembayaran->status_approval == 'pending' ? 'border-l-4 border-yellow-400' : '' }}">
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
                            @if($pembayaran->status_approval == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @elseif($pembayaran->status_approval == 'approved')
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @elseif($pembayaran->status_approval == 'rejected')
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs transition inline-flex items-center"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
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
                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4 opacity-50"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Pembayaran</h3>
                                <p class="text-sm text-gray-500">Belum ada data pembayaran yang tersedia</p>
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

@endsection