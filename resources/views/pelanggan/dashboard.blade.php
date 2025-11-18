@extends('layouts.pelanggan')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    {{-- Welcome Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Selamat Datang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row">
        {{-- Status Paket --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Status Paket
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($statusPaket == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Paket WiFi --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Paket WiFi
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ $paketWifi }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wifi fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Harga Bulanan --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Harga Bulanan
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($hargaBulanan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Tagihan Belum Lunas --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Tagihan Belum Lunas
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ $tagihanBelumLunas }} Bulan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Pelanggan --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    @if($pelanggan)
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama</strong></td>
                                <td>: {{ $pelanggan->nama }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>No HP</strong></td>
                                <td>: {{ $pelanggan->no_hp }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: {{ $pelanggan->alamat }}</td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted mb-0">Data pelanggan belum tersedia.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Riwayat Tagihan Terakhir --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Tagihan Terakhir</h6>
                    <a href="{{ route('pelanggan.tagihan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($riwayatTagihan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatTagihan as $tagihan)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($tagihan->bulan_tagihan)->format('F Y') }}</td>
                                            <td>Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                                            <td>
                                                @if($tagihan->status == 'lunas')
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted mb-0">Belum ada riwayat tagihan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi Tagihan --}}
    @if($tagihanBelumLunas > 0)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning shadow" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Peringatan Tagihan</h5>
                    <p class="mb-0">
                        Anda memiliki <strong>{{ $tagihanBelumLunas }} tagihan</strong> yang belum lunas. 
                        Silakan lakukan pembayaran untuk menghindari pemutusan layanan.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
