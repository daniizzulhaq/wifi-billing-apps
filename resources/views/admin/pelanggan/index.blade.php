@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('header', 'Data Pelanggan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Pelanggan</h3>
        <a href="{{ route('admin.pelanggan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </a>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No HP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pelanggans as $index => $pelanggan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelanggans->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelanggan->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelanggan->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelanggan->no_hp }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pelanggan->paket_wifi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($pelanggan->harga_bulanan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pelanggan->status == 'aktif')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.pelanggan.show', $pelanggan) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pelanggan.edit', $pelanggan) }}" class="text-yellow-600 hover:text-yellow-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pelanggan.destroy', $pelanggan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data pelanggan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pelanggans->links() }}
        </div>
    </div>
</div>
@endsection