<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramCamp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'program_camp';

    protected $fillable = [
        'nama',
        'slug',
        'kategori',
        'stok',
        'harga_perhari',
        'harga_satu_minggu',
        'harga_dua_minggu',
        'harga_tiga_minggu',
        'harga_satu_bulan',
        'harga_dua_bulan',
        'harga_tiga_bulan',
        'harga_enam_bulan',
        'harga_satu_tahun',
        'fasilitas',
        'thumbnail_id'
    ];

    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class, 'program_camp_id');
    }
}



