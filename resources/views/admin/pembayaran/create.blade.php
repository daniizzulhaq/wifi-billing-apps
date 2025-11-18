@extends('layouts.admin')

@section('title', 'Input Pembayaran')
@section('header', 'Input Pembayaran')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Form Input Pembayaran</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.pembayaran.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tagihan</label>
                    <select name="tagihan_id" required id="tagihan_select"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tagihan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Tagihan --</option>
                        @foreach($tagihans as $tagihan)
                        <option value="{{ $tagihan->id }}" 
                                data-nominal="{{ $tagihan->jumlah_tagihan }}"
                                data-denda="{{ $tagihan->denda }}"
                                {{ old('tagihan_id') == $tagihan->id ? 'selected' : '' }}>
                            {{ $tagihan->pelanggan->nama }} - {{ \Carbon\Carbon::parse($tagihan->bulan)->format('F Y') }} 
                            (Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                    @error('tagihan_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tanggal_bayar') border-red-500 @enderror">
                    @error('tanggal_bayar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar (Rp)</label>
                    <input type="number" name="jumlah" id="jumlah_bayar" value="{{ old('jumlah') }}" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jumlah') border-red-500 @enderror">
                    @error('jumlah')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Jumlah akan terisi otomatis saat memilih tagihan</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="metode" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('metode') border-red-500 @enderror">
                        <option value="tunai" {{ old('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ old('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                    @error('metode')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3"
                              placeholder="Tambahkan keterangan jika diperlukan..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    <i class="fas fa-save"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('tagihan_select').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const nominal = selected.getAttribute('data-nominal');
        const denda = selected.getAttribute('data-denda');
        
        if (nominal && denda) {
            const total = parseInt(nominal) + parseInt(denda);
            document.getElementById('jumlah_bayar').value = total;
        } else {
            document.getElementById('jumlah_bayar').value = '';
        }
    });
</script>
@endsection