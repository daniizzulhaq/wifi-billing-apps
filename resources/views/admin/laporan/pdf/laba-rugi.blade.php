<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi - {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-top: 25px;
        }
        .summary table {
            width: 50%;
            margin: 0 auto;
            border: none;
        }
        .summary td {
            border: none;
            padding: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN LABA RUGI</h2>
        <h3>Bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</h3>
    </div>

    {{-- ===================== PEMASUKAN ===================== --}}
    <h3>Pemasukan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kasMasuk as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data pemasukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Pemasukan</th>
                <th class="text-right">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- ===================== PENGELUARAN ===================== --}}
    <h3 style="margin-top: 30px;">Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kasKeluar as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data pengeluaran</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Pengeluaran</th>
                <th class="text-right">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- ===================== RINGKASAN ===================== --}}
    <div class="summary">
        <h3>Ringkasan Laba / Rugi</h3>
        <table>
            <tr>
                <td>Total Pemasukan</td>
                <td class="text-right">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pengeluaran</td>
                <td class="text-right">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>{{ $labaRugi >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>

        @if($saldo)
        <h3 style="margin-top: 25px;">Ringkasan Saldo</h3>
        <table>
            <tr>
                <td>Saldo Awal</td>
                <td class="text-right">Rp {{ number_format($saldo->saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Masuk</td>
                <td class="text-right">Rp {{ number_format($saldo->total_masuk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Keluar</td>
                <td class="text-right">Rp {{ number_format($saldo->total_keluar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Saldo Akhir</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($saldo->saldo_akhir, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
        @endif
    </div>

    {{-- ===================== FOOTER ===================== --}}
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>
