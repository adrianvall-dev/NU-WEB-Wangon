<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Organisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganisasiController extends Controller
{
    /**
     * Menampilkan daftar seluruh organisasi / banom.
     */
    public function index(): View
    {
        $organisasis = Organisasi::with(['ketua', 'wakilKetua'])
            ->withCount(['anggotas', 'activeAnggotas'])
            ->get();

        return view('admin.organisasi.index', compact('organisasis'));
    }

    /**
     * Form tambah organisasi baru.
     */
    public function create(): View
    {
        $kandidatAnggota = Anggota::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.organisasi.create', compact('kandidatAnggota'));
    }

    /**
     * Simpan organisasi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:100|unique:organisasi,slug',
            'deskripsi' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
            'visi' => 'nullable|string|max:2000',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string|max:500',
            'ketua_id' => 'nullable|exists:anggota,id',
            'wakil_ketua_id' => 'nullable|exists:anggota,id',
        ]);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['singkatan'] ?: $validated['nama']);
            $slug = $baseSlug;
            $counter = 1;
            while (Organisasi::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('organisasi', 'public');
        }

        $validated['misi'] = $this->cleanMisi($request->input('misi', []));

        $organisasi = Organisasi::create($validated);

        if ($organisasi->ketua_id) {
            Anggota::where('id', $organisasi->ketua_id)->update([
                'jabatan' => 'Ketua',
                'organisasi' => $organisasi->slug,
            ]);
        }

        if ($organisasi->wakil_ketua_id) {
            Anggota::where('id', $organisasi->wakil_ketua_id)->update([
                'jabatan' => 'Wakil Ketua',
                'organisasi' => $organisasi->slug,
            ]);
        }

        return redirect()->route('admin.organisasi.show', $organisasi->id)
            ->with('success', 'Organisasi '.$organisasi->nama.' berhasil ditambahkan.');
    }

    /**
     * Detail organisasi beserta struktur, visi, misi, dan anggota.
     */
    public function show(Organisasi $organisasi): View
    {
        $organisasi->load(['ketua', 'wakilKetua']);
        $anggotas = $organisasi->anggotas()->latest()->paginate(10);

        return view('admin.organisasi.show', compact('organisasi', 'anggotas'));
    }

    /**
     * Form edit organisasi.
     */
    public function edit(Organisasi $organisasi): View
    {
        $kandidatAnggota = Anggota::where('status', 'aktif')
            ->orderByRaw('CASE WHEN organisasi = ? THEN 0 ELSE 1 END', [$organisasi->slug])
            ->orderBy('nama')
            ->get();

        return view('admin.organisasi.edit', compact('organisasi', 'kandidatAnggota'));
    }

    /**
     * Perbarui organisasi.
     */
    public function update(Request $request, Organisasi $organisasi): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'slug' => 'required|string|max:100|unique:organisasi,slug,'.$organisasi->id,
            'deskripsi' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
            'visi' => 'nullable|string|max:2000',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string|max:500',
            'ketua_id' => 'nullable|exists:anggota,id',
            'wakil_ketua_id' => 'nullable|exists:anggota,id',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        if ($request->hasFile('logo')) {
            if ($organisasi->logo) {
                Storage::disk('public')->delete($organisasi->logo);
            }
            $validated['logo'] = $request->file('logo')->store('organisasi', 'public');
        }

        $validated['misi'] = $this->cleanMisi($request->input('misi', []));

        $oldKetuaId = $organisasi->ketua_id;
        $oldWakilKetuaId = $organisasi->wakil_ketua_id;

        $organisasi->update($validated);

        // Update jabatan untuk Ketua
        if ($organisasi->ketua_id) {
            if ($oldKetuaId && $oldKetuaId != $organisasi->ketua_id) {
                Anggota::where('id', $oldKetuaId)->where('organisasi', $organisasi->slug)->update(['jabatan' => 'Anggota']);
            }
            Anggota::where('organisasi', $organisasi->slug)
                ->where('id', '!=', $organisasi->ketua_id)
                ->where(function ($q) {
                    $q->where('jabatan', 'ketua')->orWhere('jabatan', 'Ketua');
                })
                ->update(['jabatan' => 'Anggota']);

            Anggota::where('id', $organisasi->ketua_id)->update([
                'jabatan' => 'Ketua',
                'organisasi' => $organisasi->slug,
            ]);
        } else {
            if ($oldKetuaId) {
                Anggota::where('id', $oldKetuaId)->where('organisasi', $organisasi->slug)->update(['jabatan' => 'Anggota']);
            }
        }

        // Update jabatan untuk Wakil Ketua
        if ($organisasi->wakil_ketua_id) {
            if ($oldWakilKetuaId && $oldWakilKetuaId != $organisasi->wakil_ketua_id) {
                Anggota::where('id', $oldWakilKetuaId)->where('organisasi', $organisasi->slug)->update(['jabatan' => 'Anggota']);
            }
            Anggota::where('organisasi', $organisasi->slug)
                ->where('id', '!=', $organisasi->wakil_ketua_id)
                ->where('jabatan', 'like', '%wakil%')
                ->update(['jabatan' => 'Anggota']);

            Anggota::where('id', $organisasi->wakil_ketua_id)->update([
                'jabatan' => 'Wakil Ketua',
                'organisasi' => $organisasi->slug,
            ]);
        } else {
            if ($oldWakilKetuaId) {
                Anggota::where('id', $oldWakilKetuaId)->where('organisasi', $organisasi->slug)->update(['jabatan' => 'Anggota']);
            }
        }

        return redirect()->route('admin.organisasi.show', $organisasi->id)
            ->with('success', 'Organisasi '.$organisasi->nama.' berhasil diperbarui.');
    }

    /**
     * Hapus organisasi.
     */
    public function destroy(Organisasi $organisasi): RedirectResponse
    {
        if ($organisasi->logo) {
            Storage::disk('public')->delete($organisasi->logo);
        }

        $nama = $organisasi->nama;
        $organisasi->delete();

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Organisasi '.$nama.' berhasil dihapus.');
    }

    /**
     * Bersihkan input butir misi.
     *
     * @param  array<mixed>|null  $misi
     * @return array<string>
     */
    private function cleanMisi(?array $misi): array
    {
        if (! is_array($misi)) {
            return [];
        }

        return array_values(array_filter(
            array_map(fn ($item) => is_string($item) ? trim($item) : '', $misi),
            fn ($item) => $item !== ''
        ));
    }
}
