<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendaftaranProgramOffline extends Model
{
    use SoftDeletes;

    protected $table = 'pendaftaran_program_offline';

    protected $fillable = [
        'trx_id',
        'nama_lengkap',
        'email',
        'no_hp',
        'asal_kota',
        'no_wali',
        'program_id',
        'period_id',
        'transport_id',
        'bukti_pembayaran',
        'status',
        'bank_id',
        'payment_type', // <-- Kolom baru tetap ada di sini
    ];

    // Relasi ke program offline
    public function program()
    {
        return $this->belongsTo(ProgramOffline::class, 'program_id');
    }

    // Relasi ke periode
    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    // Relasi ke transportasi
    public function transport()
    {
        // Pastikan nama model Transports sudah benar
        return $this->belongsTo(Transports::class, 'transport_id');
    }

    // Relasi ke bank
    public function bank()
    {
        // Pastikan nama model Banks sudah benar
        return $this->belongsTo(\App\Models\Banks::class, 'bank_id');
    }

    // Anda sepertinya punya dua relasi ke program, mungkin salah satunya bisa dihapus?
    // Jika 'program_offline_id' tidak ada di tabel, relasi ini tidak akan berfungsi.
    public function programOffline()
    {
        return $this->belongsTo(\App\Models\ProgramOffline::class, 'program_offline_id');
    }
}
