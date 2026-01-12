@extends('layouts.pelanggan')

@section('title', 'Bayar Tagihan')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bayar Tagihan</h1>
        <a href="{{ route('pelanggan.tagihan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Form Pembayaran --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave"></i> Form Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pelanggan.pembayaran.store', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                   name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                            @error('tanggal_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Transfer <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror" 
                                   name="bukti_transfer" accept="image/*" required>
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                            @error('bukti_transfer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Perhatian:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pastikan nominal transfer sesuai dengan total tagihan</li>
                                <li>Upload bukti transfer yang jelas dan terbaca</li>
                                <li>Pembayaran akan diverifikasi oleh admin maksimal 1x24 jam</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Kirim Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Detail Tagihan --}}
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-invoice"></i> Detail Tagihan
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Bulan</strong></td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($tagihan->bulan_tahun)->format('F Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jatuh Tempo</strong></td>
                            <td>:</td>
                            <td>
                                {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isPast())
                                    <br><span class="badge bg-danger small">Sudah Lewat</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tagihan</strong></td>
                            <td>:</td>
                            <td>Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        @if($tagihan->denda > 0)
                        <tr>
                            <td><strong>Denda</strong></td>
                            <td>:</td>
                            <td class="text-danger">Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <td><strong>Total Bayar</strong></td>
                            <td>:</td>
                            <td>
                                <h5 class="mb-0 text-primary">
                                    <strong>Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.') }}</strong>
                                </h5>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Info Rekening --}}
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-university"></i> Rekening Transfer
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Bank BCA</strong><br>
                        <span class="h5 text-primary">8930536084</span><br>
                        <small class="text-muted">a.n PT Zikri Rizkian</small>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <strong>Bank BRI</strong><br>
                        <span class="h5 text-primary">3768 01022083532</span><br>
                        <small class="text-muted">a.n Zikri Rizkian</small>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <strong>E-wallet DANA</strong><br>
                        <span class="h5 text-primary">082242350529</span><br>
                        <small class="text-muted">a.n Zikri Rizkian</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection