<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $nama_kamar = $request->input('nama_kamar');
        $kamar_id = $request->input('kamar_id');

        return view('camp.pembayaran', compact('nama_kamar', 'kamar_id'));
    }

    public function proses(Request $request)
    {
        // logika proses pembayaran di sini
        return redirect()->route('home')->with('success', 'Pembayaran berhasil!');
    }
}
