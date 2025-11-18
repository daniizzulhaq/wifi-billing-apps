<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function index()
    {
        $pelanggan = Auth::user()->pelanggan;

        $tagihans = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->with('pembayaran')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return view('pelanggan.tagihan.index', compact('tagihans'));
    }

    public function show($id)
    {
        $pelanggan = Auth::user()->pelanggan;

        $tagihan = Tagihan::where('id', $id)
            ->where('pelanggan_id', $pelanggan->id)
            ->with('pembayaran')
            ->firstOrFail();

        return view('pelanggan.tagihan.show', compact('tagihan'));
    }
}