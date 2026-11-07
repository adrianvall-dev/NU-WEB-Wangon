<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Support\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::orderBy('created_at', 'desc')->paginate(10);

        $totalNews = News::count();
        $publishedCount = News::where('status', 'published')->count();
        $draftCount = News::where('status', 'draft')->count();

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $rawChartData = News::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[] = $rawChartData->get($formattedDate, 0);
        }

        return view('admin.berita.index', compact(
            'newsList',
            'totalNews',
            'publishedCount',
            'draftCount',
            'chartLabels',
            'chartData'
        ));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kategori' => 'required|'.Organisasi::validationRuleWithUmum(),
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']).'-'.uniqid();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = $validated['published_at'] ?? now();
        } else {
            $validated['published_at'] = null;
        }

        News::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $berita)
    {
        return view('admin.berita.edit', ['news' => $berita]);
    }

    public function update(Request $request, News $berita)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kategori' => 'required|'.Organisasi::validationRuleWithUmum(),
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if ($berita->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']).'-'.uniqid();
        }

        if ($request->hasFile('image')) {
            if ($berita->image) {
                Storage::disk('public')->delete($berita->image);
            }
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = $validated['published_at'] ?? $berita->published_at ?? now();
        } else {
            $validated['published_at'] = null;
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $berita)
    {
        if ($berita->image) {
            Storage::disk('public')->delete($berita->image);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
