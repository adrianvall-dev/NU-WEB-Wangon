<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Support\Organisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatanList = Kegiatan::orderBy('event_date', 'desc')->paginate(10);

        $totalKegiatan = Kegiatan::count();
        $publishedCount = Kegiatan::where('status', 'published')->count();
        $draftCount = Kegiatan::where('status', '!=', 'published')->count();

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $rawChartData = Kegiatan::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[] = (int) ($rawChartData->get($formattedDate, 0));
        }

        return view('admin.kegiatan.index', compact(
            'kegiatanList',
            'totalKegiatan',
            'publishedCount',
            'draftCount',
            'chartLabels',
            'chartData'
        ));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kategori' => 'required|'.Organisasi::validationRuleWithUmum(),
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'location' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180|required_with:latitude',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $validated['latitude'] = ($request->filled('latitude')) ? (float) $request->latitude : null;
        $validated['longitude'] = ($request->filled('longitude')) ? (float) $request->longitude : null;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('kegiatan', 'public');
        }

        Kegiatan::create($validated);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kategori' => 'required|'.Organisasi::validationRuleWithUmum(),
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'location' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180|required_with:latitude',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $validated['latitude'] = ($request->filled('latitude')) ? (float) $request->latitude : null;
        $validated['longitude'] = ($request->filled('longitude')) ? (float) $request->longitude : null;

        if ($request->hasFile('image')) {
            if ($kegiatan->image) {
                Storage::disk('public')->delete($kegiatan->image);
            }
            $validated['image'] = $request->file('image')->store('kegiatan', 'public');
        }

        $kegiatan->update($validated);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->image) {
            Storage::disk('public')->delete($kegiatan->image);
        }

        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
