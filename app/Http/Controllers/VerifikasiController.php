<?php

namespace App\Http\Controllers;

use App\Models\Anggota;

class VerifikasiController extends Controller
{
    public function show($kode)
    {
        $anggota = Anggota::where('kode_verifikasi', $kode)
            ->orWhere('kode_pendaftaran', $kode)
            ->orWhere('nomor_anggota', $kode)
            ->orWhere('id', $kode)
            ->first();

        return view('verifikasi.show', compact('anggota'));
    }
}
