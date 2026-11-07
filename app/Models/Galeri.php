<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'judul',
        'kategori',
        'gambar',
        'deskripsi',
        'lokasi',
        'jumlah_peserta',
        'status',
    ];

    protected $casts = [
        'jumlah_peserta' => 'integer',
    ];
}
