<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'type',
        'image_path',
        'thumbnail_path',
        'video_url',
        'caption',
    ];

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isLocalVideo(): bool
    {
        return $this->type === 'video' && !$this->video_url && $this->image_path;
    }

    public function isYoutubeVideo(): bool
    {
        return $this->type === 'video' && (bool) $this->video_url;
    }

    public function getYoutubeEmbedUrl(): ?string
    {
        if (!$this->video_url) return null;
        // Gunakan helper yang sudah di-autoload untuk mendukung semua format URL YouTube
        // termasuk: ?v=ID, ?si=...&v=ID, youtu.be/ID, shorts/ID, embed/ID
        $id = getYoutubeVideoId($this->video_url);
        return $id ? 'https://www.youtube.com/embed/' . $id : null;
    }

    // Relasi: gambar ini milik satu galeri
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
