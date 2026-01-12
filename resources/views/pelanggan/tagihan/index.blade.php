@extends('layouts.pelanggan')

@section('title', 'Tagihan Saya')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Tagihan Saya</h1>

    {{-- Info Card --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Tagihan Belum Lunas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tagihans->where('status', 'belum_lunas')->count() }} Bulan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tagihan Lunas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tagihans->where('status', 'lunas')->count() }} Bulan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Nominal Belum Lunas
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($tagihans->where('status', 'belum_lunas')->sum('jumlah_tagihan'), 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Tagihan Belum Lunas --}}
    @if($tagihans->where('status', 'belum_lunas')->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow mb-4" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Perhatian!</strong> 
            Anda memiliki {{ $tagihans->where('status', 'belum_lunas')->count() }} tagihan yang belum dibayar. 
            Segera lakukan pembayaran untuk menghindari pemutusan layanan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabel Tagihan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Tagihan</h6>
            <a href="{{ route('pelanggan.pembayaran.index') }}" class="btn btn-sm btn-info">
                <i class="fas fa-history"></i> Riwayat Pembayaran
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Bulan Tagihan</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Jumlah Tagihan</th>
                            <th>Denda</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihans as $tagihan)
                            <tr class="{{ $tagihan->status == 'belum_lunas' ? 'table-warning' : '' }}">
                                <td>{{ $tagihans->firstItem() + $loop->index }}</td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($tagihan->bulan)->format('F Y') }}</strong>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') }}</td>
                                <td>Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                                <td>
                                    @if($tagihan->denda > 0)
                                        <span class="text-danger">
                                            Rp {{ number_format($tagihan->denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($tagihan->status == 'lunas')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Lunas
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($tagihan->status == 'belum_lunas')
                                        <a href="{{ route('pelanggan.pembayaran.create', $tagihan->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-money-bill"></i> Bayar
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data tagihan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $tagihans->links() }}
            </div>
        </div>
    </div>

    {{-- Informasi Pembayaran --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle"></i> Informasi Pembayaran
            </h6>
        </div>
        <div class="card-body">
            <p class="mb-2"><strong>Cara Pembayaran:</strong></p>
            <ol class="mb-3">
                <li>Klik tombol <strong>"Bayar"</strong> pada tagihan yang ingin dibayar</li>
                <li>Lakukan transfer ke rekening yang tertera</li>
                <li>Upload bukti transfer</li>
                <li>Tunggu verifikasi dari admin (maksimal 1x24 jam)</li>
            </ol>
            <hr>
            <p class="mb-2"><strong>Rekening Tujuan Transfer:</strong></p>
            <ul class="mb-0">
                <li><strong>Bank BCA:</strong> 8930536084 a.n Zikri Rizkian</li>
                <li><strong>Bank BRI:</strong> 3768 01022083532 a.n Zikri Rizkian</li>
                <li><strong>E-wallet DANA:</strong> 082242350529 a.n Zikri Rizkian</li>
            </ul>
            <hr>
            <p class="mb-0 text-muted small">
                <i class="fas fa-exclamation-triangle text-warning"></i>
                Pembayaran yang melewati tanggal jatuh tempo akan dikenakan denda sesuai ketentuan yang berlaku.
            </p>
        </div>
    </div>
</div>
@endsection