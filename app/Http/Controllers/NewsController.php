<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Support\Organisasi;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::where('status', 'published');

        // Fitur Pencarian (Opsional - menyesuaikan fitur search bar)
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('content', 'like', '%'.$request->search.'%');
            });
        }

        // Filter kategori organisasi (default: semua kategori)
        if ($request->has('kategori') && in_array($request->kategori, Organisasi::allWithUmum())) {
            $query->where('kategori', $request->kategori);
        }

        $newsList = $query->orderBy('published_at', 'desc')->paginate(6)->withQueryString();

        return view('berita.index', compact('newsList'));
    }

    public function show($slug)
    {
        // 1. Ambil berita utama yang dipublikasikan
        $news = News::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // 2. Ambil 4 berita lainnya (kecuali berita yang sedang dibuka) untuk sidebar
        $relatedNews = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // 3. Kirim variabel $news dan $relatedNews ke view
        return view('berita.show', compact('news', 'relatedNews'));
    }
}
