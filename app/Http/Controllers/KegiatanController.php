<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Support\Organisasi;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::where('status', 'published');

        // Fitur pencarian kegiatan berdasarkan judul atau lokasi
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('location', 'like', '%'.$request->search.'%');
            });
        }

        // Filter kategori organisasi (default: semua kategori)
        if ($request->has('kategori') && in_array($request->kategori, Organisasi::allWithUmum())) {
            $query->where('kategori', $request->kategori);
        }

        $kegiatanList = $query->orderBy('event_date', 'asc')->paginate(6)->withQueryString();

        return view('kegiatan.index', compact('kegiatanList'));
    }

    public function show($id)
    {
        // 1. Ambil kegiatan utama yang dipublikasikan
        $kegiatan = Kegiatan::where('status', 'published')
            ->findOrFail($id);

        // 2. Ambil 4 kegiatan lainnya (kecuali yang sedang dibuka) untuk sidebar
        $relatedKegiatan = Kegiatan::where('status', 'published')
            ->where('id', '!=', $kegiatan->id)
            ->orderBy('event_date', 'desc')
            ->take(4)
            ->get();

        // 3. Kirim $kegiatan dan $relatedKegiatan ke view
        return view('kegiatan.show', compact('kegiatan', 'relatedKegiatan'));
    }
}
