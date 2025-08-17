<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramOnline;
use App\Models\ProgramOffline;

class ProgramInggrisController extends Controller
{
    public function showInggris()
    {
        $onlinePrograms = ProgramOnline::where('program_bahasa', 'Inggris')
            ->where('is_active', 1)
            ->get();

        $offlinePrograms = ProgramOffline::where('program_bahasa', 'Inggris')
            ->where('is_active', 1)
            ->get();

        return view('Landingpage.inggris', compact('onlinePrograms', 'offlinePrograms'));
    }
}
