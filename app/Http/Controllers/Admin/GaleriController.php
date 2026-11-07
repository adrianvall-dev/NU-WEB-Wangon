<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FotoKegiatan;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\Pengaturan;
use App\Support\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Tampilkan daftar History Kegiatan (kegiatan yang sudah selesai)
     * sebagai album dokumentasi galeri.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::selesai()->with(['fotos']);

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

        $kegiatanList = $query->orderBy('event_date', 'desc')->paginate(9)->withQueryString();
        $pengaturan = Pengaturan::ambil();

        $totalDokumentasi = FotoKegiatan::where(function ($q) {
            $q->where('tipe', 'foto')->orWhereNull('tipe');
        })->count();
        $totalVideo = FotoKegiatan::where('tipe', 'video')->count();
        $totalKegiatanSelesai = Kegiatan::selesai()->count();
        $totalKegiatanDenganDokumentasi = Kegiatan::selesai()->has('fotos')->count();

        return view('admin.galeri.index', compact(
            'kegiatanList',
            'pengaturan',
            'totalDokumentasi',
            'totalVideo',
            'totalKegiatanSelesai',
            'totalKegiatanDenganDokumentasi'
        ));
    }

    /**
     * Buka detail history kegiatan untuk mengelola foto-foto dokumentasi.
     */
    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load(['fotos']);

        return view('admin.galeri.show', compact('kegiatan'));
    }

    /**
     * Unggah banyak foto dokumentasi sekaligus untuk satu kegiatan.
     */
    public function uploadFotos(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'fotos' => 'required|array|min:1',
            'fotos.*' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'caption' => 'nullable|string|max:255',
        ], [
            'fotos.required' => 'Pilih minimal satu file foto dokumentasi untuk diunggah.',
            'fotos.*.image' => 'File yang diunggah harus berupa gambar (JPG, PNG, WEBP, GIF).',
            'fotos.*.max' => 'Ukuran maksimal tiap foto adalah 5 MB.',
        ]);

        $uploadedCount = 0;
        $highestOrder = (int) $kegiatan->fotos()->max('sort_order');

        foreach ($request->file('fotos') as $file) {
            $path = $file->store('kegiatan/galeri', 'public');

            $kegiatan->fotos()->create([
                'file_path' => $path,
                'tipe' => 'foto',
                'caption' => $request->filled('caption') ? $request->caption : null,
                'sort_order' => ++$highestOrder,
            ]);

            $uploadedCount++;
        }

        return redirect()->route('admin.galeri.show', $kegiatan->id)
            ->with('success', "{$uploadedCount} foto dokumentasi kegiatan berhasil diunggah.");
    }

    /**
     * Unggah video dokumentasi untuk satu kegiatan.
     */
    public function uploadVideo(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'video' => [
                'required',
                'file',
                'mimes:mp4,mov,webm,ogg,mkv,avi',
                'max:51200', // 50 MB
            ],
            'caption' => 'nullable|string|max:255',
        ], [
            'video.required' => 'Pilih file video dokumentasi untuk diunggah.',
            'video.file' => 'File yang dipilih bukan file yang valid.',
            'video.uploaded' => 'File video gagal diunggah ke server. Pastikan ukuran video tidak melebihi 50 MB dan pengaturan PHP server mengizinkan upload 50 MB.',
            'video.mimes' => 'Format video harus berupa MP4, WebM, MOV, OGG, MKV, atau AVI.',
            'video.max' => 'Ukuran maksimal file video adalah 50 MB.',
        ]);

        if (! $request->hasFile('video') || ! $request->file('video')->isValid()) {
            $errorMessage = $request->file('video') ? $request->file('video')->getErrorMessage() : 'File video tidak ditemukan dalam request.';

            return back()
                ->withErrors(['video' => 'File video tidak valid atau gagal diterima server: '.$errorMessage])
                ->withInput();
        }

        $file = $request->file('video');
        $path = $file->store('kegiatan/video', 'public');

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return back()
                ->withErrors(['video' => 'Gagal menyimpan file video ke direktori penyimpanan server.'])
                ->withInput();
        }

        $highestOrder = (int) $kegiatan->fotos()->max('sort_order');

        $kegiatan->fotos()->create([
            'file_path' => $path,
            'tipe' => 'video',
            'caption' => $request->filled('caption') ? $request->caption : null,
            'sort_order' => ++$highestOrder,
        ]);

        return redirect()->route('admin.galeri.show', $kegiatan->id)
            ->with('success', 'Video dokumentasi kegiatan berhasil diunggah.');
    }

    /**
     * Hapus satu media dokumentasi kegiatan (foto atau video).
     */
    public function destroyFoto(FotoKegiatan $foto)
    {
        $kegiatanId = $foto->kegiatan_id;

        if ($foto->file_path) {
            Storage::disk('public')->delete($foto->file_path);
        }

        $foto->delete();

        return redirect()->route('admin.galeri.show', $kegiatanId)
            ->with('success', 'File dokumentasi berhasil dihapus.');
    }

    /**
     * Simpan pengaturan tahun berdiri.
     */
    public function updatePengaturan(Request $request)
    {
        $validated = $request->validate([
            'tahun_berdiri' => 'required|integer|min:1800|max:2100',
        ]);

        $pengaturan = Pengaturan::ambil();
        $pengaturan->update($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Tahun berdiri berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | Compatibility Methods (agar rute legacy tidak error jika dipanggil)
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return redirect()->route('admin.galeri.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.galeri.index');
    }

    public function edit(Galeri $galeri)
    {
        return redirect()->route('admin.galeri.index');
    }

    public function update(Request $request, Galeri $galeri)
    {
        return redirect()->route('admin.galeri.index');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->gambar) {
            Storage::disk('public')->delete($galeri->gambar);
        }
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil dihapus.');
    }
}
