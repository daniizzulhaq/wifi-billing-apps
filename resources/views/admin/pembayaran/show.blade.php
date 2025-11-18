@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')
@section('header', 'Verifikasi Pembayaran')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pembayaran.pending') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition inline-block">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Detail Pembayaran --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-file-invoice-dollar"></i> Detail Pembayaran
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Pelanggan</div>
                        <div class="col-span-2">{{ optional($pembayaran->tagihan->pelanggan)->nama ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">No HP</div>
                        <div class="col-span-2">{{ optional($pembayaran->tagihan->pelanggan)->no_hp ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Alamat</div>
                        <div class="col-span-2">{{ optional($pembayaran->tagihan->pelanggan)->alamat ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Bulan Tagihan</div>
                        <div class="col-span-2">
                            {{ optional($pembayaran->tagihan)->bulan 
                                ? \Carbon\Carbon::parse($pembayaran->tagihan->bulan)->format('F Y') 
                                : '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Tanggal Bayar</div>
                        <div class="col-span-2">
                            {{ $pembayaran->tanggal_bayar 
                                ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d F Y') 
                                : '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Jumlah Transfer</div>
                        <div class="col-span-2">
                            <span class="text-xl font-bold text-blue-600">Rp {{ number_format($pembayaran->jumlah ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Metode Pembayaran</div>
                        <div class="col-span-2">
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">{{ ucfirst($pembayaran->metode ?? '-') }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Status</div>
                        <div class="col-span-2">
                            @if($pembayaran->status_approval == 'pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                            @elseif($pembayaran->status_approval == 'approved')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Disetujui</span>
                            @elseif($pembayaran->status_approval == 'rejected')
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Ditolak</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">-</span>
                            @endif
                        </div>
                    </div>
                    @if($pembayaran->keterangan)
                    <div class="grid grid-cols-3 gap-4">
                        <div class="font-medium text-gray-700">Keterangan</div>
                        <div class="col-span-2">{{ $pembayaran->keterangan }}</div>
                    </div>
                    @endif
                </div>

                @if($pembayaran->status_approval == 'pending')
                    <hr class="my-6">
                    <h6 class="font-semibold text-gray-800 mb-4">Verifikasi Pembayaran</h6>
                    
                    {{-- Form Approve --}}
                    <form action="{{ route('admin.pembayaran.approve', $pembayaran->id) }}" 
                          method="POST" class="mb-3" id="approveForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="catatan_admin" rows="2" 
                                      placeholder="Tambahkan catatan jika diperlukan"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" 
                                onclick="return confirm('Setujui pembayaran ini?')"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                            <i class="fas fa-check-circle"></i> Setujui Pembayaran
                        </button>
                    </form>

                    {{-- Button Reject --}}
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        <i class="fas fa-times-circle"></i> Tolak Pembayaran
                    </button>
                @endif

                @if($pembayaran->status_approval != 'pending')
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                            <div>
                                <strong class="text-blue-900">Informasi Verifikasi</strong>
                                <div class="text-sm text-blue-800 mt-1">
                                    Status: <strong>{{ $pembayaran->status_approval == 'approved' ? 'Disetujui' : 'Ditolak' }}</strong><br>
                                    Tanggal: {{ $pembayaran->tanggal_approval ? $pembayaran->tanggal_approval->format('d F Y H:i') : '-' }}<br>
                                    @if($pembayaran->catatan_admin)
                                        Catatan: {{ $pembayaran->catatan_admin }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Bukti Transfer --}}
<div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-image"></i> Bukti Transfer
            </h3>
        </div>
        <div class="p-6 text-center">
            @if($pembayaran->bukti_transfer)
                <img src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" 
                     class="w-full rounded border border-gray-300 cursor-pointer hover:shadow-lg transition"
                     alt="Bukti Transfer"
                     onclick="document.getElementById('imageModal').classList.remove('hidden')">
                <div class="mt-4">
                    <a href="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition inline-block"
                       target="_blank">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            @else
                <p class="text-gray-500">Tidak ada bukti transfer</p>
            @endif
        </div>
    </div>
</div>


{{-- Modal Reject --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('admin.pembayaran.reject', $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-red-500 text-white px-4 py-3 rounded-t-md -mx-5 -mt-5 mb-4">
                <h5 class="font-semibold">Tolak Pembayaran</h5>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mt-1 mr-2"></i>
                    <p class="text-sm text-yellow-700">
                        Anda akan menolak pembayaran ini. Pastikan Anda memberikan alasan yang jelas.
                    </p>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan_admin" rows="4" required
                          placeholder="Jelaskan alasan penolakan..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 @error('catatan_admin') border-red-500 @enderror"></textarea>
                @error('catatan_admin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                    <i class="fas fa-times-circle"></i> Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
