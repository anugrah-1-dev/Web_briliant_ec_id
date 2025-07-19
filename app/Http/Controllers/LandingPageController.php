<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\ProgramOffline;
use App\Models\ProgramOnline;
use App\Models\ProgramCamp;
use App\Models\Sosmed;
use App\Models\Customer_Service;


class LandingPageController extends Controller
{
    public function index()
    {
        $programs        = Program::orderBy('id', 'asc')->get();
        $galleries       = Gallery::where('status', 1)->with('images')->latest()->get();
        $offlinePrograms = ProgramOffline::where('is_active', 1)->latest()->get();
        $onlinePrograms  = ProgramOnline::where('is_active', 1)->latest()->get();
        $camps           = ProgramCamp::orderBy('id', 'asc')->get();
        $sosmed          = Sosmed::all();
        $contactServices = Customer_Service::all();
        // Periksa apakah ada data sosmed sama sekali


        // Kelompokkan berdasarkan platform sosial media
        $groupedSosmed = [
            'YouTube'   => [],
            'Instagram' => [],
            'Facebook'  => [],
            'TikTok'    => [],
        ];
        $hasSosmed = collect($groupedSosmed)->flatten(1)->isNotEmpty();

        foreach ($sosmed as $item) {
            $url = strtolower($item->url); // lowercase untuk akurasi pencarian

            if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                $groupedSosmed['YouTube'][] = $item;
            } elseif (str_contains($url, 'instagram.com')) {
                $groupedSosmed['Instagram'][] = $item;
            } elseif (str_contains($url, 'facebook.com')) {
                $groupedSosmed['Facebook'][] = $item;
            } elseif (str_contains($url, 'tiktok.com')) {
                $groupedSosmed['TikTok'][] = $item;
            }
        }

      
        
        return view('landingpage', [
            'offlinePrograms' => $offlinePrograms,
            'onlinePrograms'  => $onlinePrograms,
            'programs'        => $programs,
            'galleries'       => $galleries,
            'camps'           => $camps,
            'contactServices' => $contactServices,
            'groupedSosmed' => $groupedSosmed,
            'hasSosmed' => $hasSosmed,

        ]);
    }

    // Detail program offline (untuk public)// Di controller publik (misal: ProgramOfflinePublicController)
    public function showOfflinePublic(ProgramOffline $program)
    {
        if (!$program->is_active) {
            abort(404);
        }

        return view('programs.offline.show', compact('program'));
    }

    public function showOnlinePublic(ProgramOnline $program)
    {
        return view('admin.programs.online.show', compact('program'));
    }
}
