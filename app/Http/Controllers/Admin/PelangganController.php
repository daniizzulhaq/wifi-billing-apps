<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('user')->latest()->paginate(10);
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'paket_wifi' => 'required|string|max:50',
            'harga_bulanan' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();
        try {
            // Buat user untuk pelanggan
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make('123456'), // Password default
                'role' => 'pelanggan',
            ]);

            // Buat data pelanggan
            Pelanggan::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'paket_wifi' => $validated['paket_wifi'],
                'harga_bulanan' => $validated['harga_bulanan'],
                'status' => $validated['status'],
            ]);

            DB::commit();
            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Pelanggan berhasil ditambahkan. Password default: 123456');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['user', 'tagihans']);
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'paket_wifi' => 'required|string|max:50',
            'harga_bulanan' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();
        try {
            $pelanggan->update($validated);
            
            // Update nama di user
            $pelanggan->user->update([
                'name' => $validated['nama'],
            ]);

            DB::commit();
            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Pelanggan $pelanggan)
    {
        try {
            $pelanggan->user->delete(); // Akan cascade delete pelanggan
            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}