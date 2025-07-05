<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::query();

        if ($request->has('search') && $request->search != '') {
            // Diubah untuk mencari berdasarkan 'judul_konten'
            $query->where('judul_konten', 'like', '%' . $request->search . '%');
        }

        $programs = $query->latest()->get(); // Menggunakan latest() untuk mengambil data terbaru
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        // Validasi disesuaikan dengan kolom baru (dalam Bahasa Indonesia)
        $request->validate([
            'judul_konten' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'keunggulan' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $input = $request->all();

        if ($gambar = $request->file('gambar')) {
            $destinationPath = 'uploads/programs/';
            $namaGambar = date('YmdHis') . "." . $gambar->getClientOriginalExtension();
            $gambar->move($destinationPath, $namaGambar);
            $input['gambar'] = $namaGambar;
        }
        
        // Handle checkbox dengan nama baru
        $input['status_aktif_default'] = $request->has('status_aktif_default') ? 1 : 0;

        Program::create($input);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        // Validasi disesuaikan untuk proses update
        $request->validate([
            'judul_konten' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'keunggulan' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $input = $request->all();

        if ($gambar = $request->file('gambar')) {
            // Hapus gambar lama jika ada
            if ($program->gambar && file_exists(public_path('uploads/programs/' . $program->gambar))) {
                unlink(public_path('uploads/programs/' . $program->gambar));
            }

            $destinationPath = 'uploads/programs/';
            $namaGambar = date('YmdHis') . "." . $gambar->getClientOriginalExtension();
            $gambar->move($destinationPath, $namaGambar);
            $input['gambar'] = $namaGambar;
        } else {
            unset($input['gambar']);
        }
        
        $input['status_aktif_default'] = $request->has('status_aktif_default') ? 1 : 0;
        
        $program->update($input);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        // Hapus gambar dengan nama kolom baru
        if ($program->gambar && file_exists(public_path('uploads/programs/' . $program->gambar))) {
            unlink(public_path('uploads/programs/' . $program->gambar));
        }

        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
}