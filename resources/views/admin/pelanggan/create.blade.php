@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')
@section('header', 'Tambah Pelanggan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Form Tambah Pelanggan</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.pelanggan.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Paket WiFi</label>
                    <select name="paket_wifi" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Paket</option>
                        <option value="10 Mbps" {{ old('paket_wifi') == '5 Mbps' ? 'selected' : '' }}>5 Mbps</option>
                        <option value="10 Mbps" {{ old('paket_wifi') == '10 Mbps' ? 'selected' : '' }}>10 Mbps</option>
                         <option value="10 Mbps" {{ old('paket_wifi') == '15 Mbps' ? 'selected' : '' }}>15 Mbps</option>
                        <option value="20 Mbps" {{ old('paket_wifi') == '20 Mbps' ? 'selected' : '' }}>20 Mbps</option>
                        <option value="50 Mbps" {{ old('paket_wifi') == '50 Mbps' ? 'selected' : '' }}>50 Mbps</option>
                        <option value="100 Mbps" {{ old('paket_wifi') == '100 Mbps' ? 'selected' : '' }}>100 Mbps</option>
                    </select>
                    @error('paket_wifi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Bulanan (Rp)</label>
                    <input type="number" name="harga_bulanan" value="{{ old('harga_bulanan') }}" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('harga_bulanan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.pelanggan.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

            <p class="text-sm text-gray-500 mt-4">
                <i class="fas fa-info-circle"></i> Password default untuk pelanggan baru adalah: <strong>123456</strong>
            </p>
        </form>
    </div>
</div>
@endsection