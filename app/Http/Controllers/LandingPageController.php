<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program; // <-- PENTING: Import model Program

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page)
     * beserta data yang diperlukan.
     */
    public function index()
    {
        // 1. Ambil semua data dari tabel 'programs'
        $programs = Program::orderBy('id', 'asc')->get();

        // 2. Kirim data tersebut ke view 'landingpage'
        //    Variabel $programs sekarang akan tersedia di dalam view
        return view('landingpage', [
            'programs' => $programs
        ]);
    }
}