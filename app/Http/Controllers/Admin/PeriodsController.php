<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodsController extends Controller
{
    // TAMPILKAN DATA
    public function index()
    {
        $periods = Period::orderBy('date', 'desc')->paginate(10);
        return view('admin.periods.index', compact('periods'));
    }

    // TAMBAH DATA
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:periods,date',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Period::query()->update(['is_active' => false]);
        }

        Period::create([
            'date' => $request->date,
            'is_active' => $isActive,
        ]);

        return redirect()->back()->with('alert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Periode berhasil ditambahkan.'
        ]);
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date|unique:periods,date,' . $id,
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Period::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $period = Period::findOrFail($id);
        $period->update([
            'date' => $request->date,
            'is_active' => $isActive,
        ]);

        return redirect()->back()->with('alert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Periode berhasil diperbarui.'
        ]);
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $period = Period::findOrFail($id);

        if ($period->is_active) {
            // Bisa otomatis aktifkan periode terbaru kalau dibutuhkan
        }

        $period->delete();

        return redirect()->back()->with('alert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Periode berhasil dihapus.'
        ]);
    }
}
