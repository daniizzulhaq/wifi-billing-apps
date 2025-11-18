@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Pelanggan -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Pelanggan</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $totalPelanggan }}</p>
            </div>
        </div>
    </div>

    <!-- Total Tagihan Bulan Ini -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                <i class="fas fa-file-invoice text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Tagihan Bulan Ini</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($totalTagihanBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Total Pembayaran Bulan Ini -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                <i class="fas fa-money-bill text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Pembayaran Bulan Ini</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($totalPembayaranBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Tagihan Belum Dibayar -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Belum Dibayar</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $tagihanBelumDibayar }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Saldo -->
<div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow p-6 mb-6 text-white">
    <h3 class="text-lg font-semibold mb-2"><i class="fas fa-wallet"></i> Saldo Kas</h3>
    <p class="text-3xl font-bold">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
</div>

<!-- Tagihan Jatuh Tempo -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-clock text-red-500"></i> Tagihan Jatuh Tempo
        </h3>
    </div>
    <div class="p-6">
        @if($tagihanJatuhTempo->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tagihanJatuhTempo as $tagihan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tagihan->pelanggan->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tagihan->bulan_format }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-red-600">{{ $tagihan->jatuh_tempo->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Tidak ada tagihan yang jatuh tempo</p>
        @endif
    </div>
</div>
@endsection