<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Support\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::query();

        if ($request->has('organisasi') && in_array($request->organisasi, Organisasi::all())) {
            $query->where('organisasi', $request->organisasi);
        }

        $totalAnggota = (clone $query)->count();
        $aktifCount = (clone $query)->where('status', 'aktif')->count();
        $banomPemudaCount = (clone $query)->whereIn('organisasi', ['ansor', 'banser', 'pagarnusa', 'fatayat'])->count();
        $muslimatBanomCount = (clone $query)->whereIn('organisasi', ['muslimat', 'fatayat', 'ippnu'])->count();

        $anggotaList = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('admin.anggota.index', compact(
            'anggotaList',
            'totalAnggota',
            'aktifCount',
            'banomPemudaCount',
            'muslimatBanomCount'
        ));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organisasi' => 'required|'.Organisasi::validationRule(),
            'nama' => 'required|array|min:1',
            'nama.*' => 'required|string|max:255',
            'jabatan.*' => 'nullable|string|max:255',
            'alamat.*' => 'nullable|string',
            'no_hp.*' => 'nullable|string|max:20',
            'foto.*' => 'nullable|image|max:2048',
        ]);

        foreach ($validated['nama'] as $index => $nama) {
            $fotoPath = null;
            if ($request->hasFile("foto.$index")) {
                $fotoPath = $request->file("foto.$index")->store('anggota', 'public');
            }

            Anggota::create([
                'nama' => $nama,
                'organisasi' => $validated['organisasi'],
                'jabatan' => $validated['jabatan'][$index] ?? null,
                'alamat' => $validated['alamat'][$index] ?? null,
                'no_hp' => $validated['no_hp'][$index] ?? null,
                'foto' => $fotoPath,
                'status' => 'aktif',
                'status_pendaftaran' => 'approved',
                'nomor_anggota' => Anggota::buatNomorAnggota($validated['organisasi']),
                'kode_pendaftaran' => Anggota::buatKodePendaftaran(),
                'kode_verifikasi' => Anggota::buatKodeVerifikasi(),
            ]);
        }

        $jumlah = count($validated['nama']);

        return redirect()->route('admin.anggota.index')->with('success', "$jumlah anggota berhasil ditambahkan.");
    }

    public function edit(Anggota $anggotum)
    {
        return view('admin.anggota.edit', ['anggota' => $anggotum]);
    }

    public function update(Request $request, Anggota $anggotum)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'organisasi' => 'required|'.Organisasi::validationRule(),
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('foto')) {
            if ($anggotum->foto) {
                Storage::disk('public')->delete($anggotum->foto);
            }
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        $anggotum->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggotum)
    {
        if ($anggotum->foto) {
            Storage::disk('public')->delete($anggotum->foto);
        }

        $anggotum->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
