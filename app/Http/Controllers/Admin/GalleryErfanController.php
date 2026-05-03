<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryErfanController extends Controller
{
    public function index()
    {
        $galleries = Gallery::erfan()->withCount('images')->latest()->paginate(10);
        return view('admin.galleries_erfan.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries_erfan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|boolean',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'videos.*'    => 'nullable|mimes:mp4,mov,avi,mkv,webm|max:102400',
            'video_covers.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_urls'  => 'nullable|string',
        ]);

        $gallery = Gallery::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
            'category'    => 'erfan',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('galleries/erfan', 'public');
                $gallery->images()->create([
                    'type'       => 'image',
                    'image_path' => $path,
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            $covers = $request->file('video_covers') ?? [];
            foreach ($request->file('videos') as $idx => $video) {
                $path = $video->store('galleries/erfan/videos', 'public');
                $coverPath = null;
                if (isset($covers[$idx]) && $covers[$idx]->isValid()) {
                    $coverPath = $covers[$idx]->store('galleries/erfan/covers', 'public');
                }
                $gallery->images()->create([
                    'type'           => 'video',
                    'image_path'     => $path,
                    'video_url'      => null,
                    'thumbnail_path' => $coverPath,
                ]);
            }
        }

        if ($request->filled('video_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $request->video_urls)));
            foreach ($urls as $url) {
                $gallery->images()->create([
                    'type'       => 'video',
                    'image_path' => null,
                    'video_url'  => $url,
                ]);
            }
        }

        return redirect()->route('admin.galleries-erfan.index')->with('success', 'Galeri Erfan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $gallery = Gallery::erfan()->with('images')->findOrFail($id);
        return view('admin.galleries_erfan.show', compact('gallery'));
    }

    public function edit($id)
    {
        $gallery = Gallery::erfan()->with('images')->findOrFail($id);
        return view('admin.galleries_erfan.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::erfan()->findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|boolean',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'videos.*'    => 'nullable|mimes:mp4,mov,avi,mkv,webm|max:102400',
            'video_covers.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_urls'  => 'nullable|string',
        ]);

        $gallery->update([
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('galleries/erfan', 'public');
                $gallery->images()->create([
                    'type'       => 'image',
                    'image_path' => $path,
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            $covers = $request->file('video_covers') ?? [];
            foreach ($request->file('videos') as $idx => $video) {
                $path = $video->store('galleries/erfan/videos', 'public');
                $coverPath = null;
                if (isset($covers[$idx]) && $covers[$idx]->isValid()) {
                    $coverPath = $covers[$idx]->store('galleries/erfan/covers', 'public');
                }
                $gallery->images()->create([
                    'type'           => 'video',
                    'image_path'     => $path,
                    'video_url'      => null,
                    'thumbnail_path' => $coverPath,
                ]);
            }
        }

        if ($request->filled('video_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $request->video_urls)));
            foreach ($urls as $url) {
                $gallery->images()->create([
                    'type'       => 'video',
                    'image_path' => null,
                    'video_url'  => $url,
                ]);
            }
        }

        return redirect()->route('admin.galleries-erfan.index')->with('success', 'Galeri Erfan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::erfan()->with('images')->findOrFail($id);

        foreach ($gallery->images as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $gallery->delete();

        return redirect()->route('admin.galleries-erfan.index')->with('success', 'Galeri Erfan berhasil dihapus.');
    }

    public function updateImage(Request $request, $id)
    {
        $image = GalleryImage::findOrFail($id);

        $request->validate([
            'video'       => 'nullable|mimes:mp4,mov,avi,mkv,webm|max:102400',
            'video_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('video')) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->image_path = $request->file('video')->store('galleries/erfan/videos', 'public');
        }

        if ($request->hasFile('video_cover')) {
            if ($image->thumbnail_path && Storage::disk('public')->exists($image->thumbnail_path)) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
            $image->thumbnail_path = $request->file('video_cover')->store('galleries/erfan/covers', 'public');
        }

        $image->save();

        return back()->with('success', 'Video berhasil diperbarui.');
    }

    public function destroyImage($id)
    {
        $image = GalleryImage::findOrFail($id);

        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Media berhasil dihapus.');
    }
}
