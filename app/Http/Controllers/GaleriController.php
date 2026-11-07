<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Support\Organisasi;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Halaman Utama Galeri Publik.
     * Menampilkan kegiatan-kegiatan yang sudah selesai dan memiliki dokumentasi foto.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::selesai()
            ->where(function ($q) {
                $q->has('fotos')
                    ->orWhereNotNull('image');
            })
            ->with(['fotos']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('kategori') && in_array($request->kategori, Organisasi::allWithUmum())) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('media') && in_array($request->media, ['foto', 'video', 'semua'])) {
            if ($request->media === 'video') {
                $query->whereHas('fotos', function ($q) {
                    $q->where('tipe', 'video');
                });
            } elseif ($request->media === 'foto') {
                $query->where(function ($q) {
                    $q->whereHas('fotos', function ($f) {
                        $f->where('tipe', 'foto')->orWhereNull('tipe');
                    })->orWhereNotNull('image');
                });
            }
        }

        $galeriList = $query->orderBy('event_date', 'desc')->paginate(9)->withQueryString();

        return view('galeri.index', compact('galeriList'));
    }

    /**
     * Halaman Detail Dokumentasi Galeri Kegiatan.
     * Menampilkan informasi kegiatan asli + seluruh foto dokumentasi dalam grid responsif dan lightbox.
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::selesai()
            ->with(['fotos'])
            ->findOrFail($id);

        $relatedGaleri = Kegiatan::selesai()
            ->where('id', '!=', $kegiatan->id)
            ->where(function ($q) {
                $q->has('fotos')
                    ->orWhereNotNull('image');
            })
            ->with(['fotos'])
            ->orderBy('event_date', 'desc')
            ->take(3)
            ->get();

        return view('galeri.show', compact('kegiatan', 'relatedGaleri'));
    }
}
