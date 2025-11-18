@extends('layouts.pelanggan')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Detail Pembayaran</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Bulan Tagihan:</strong> {{ \Carbon\Carbon::parse($pembayaran->tagihan->bulan_tagihan)->format('F Y') }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d F Y') }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
            <p><strong>Status:</strong>
                @if($pembayaran->status_approval == 'pending')
                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                @elseif($pembayaran->status_approval == 'approved')
                    <span class="badge bg-success">Disetujui</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif
            </p>

            @if($pembayaran->bukti_transfer)
                <hr>
                <img src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" class="img-fluid rounded" alt="Bukti Transfer">
            @endif
        </div>
    </div>
</div>
@endsection
