<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Support\Organisasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PendaftaranAnggotaController extends Controller
{
    public function create()
    {
        $organisasiOptions = $this->getOrganisasiOptions();

        return view('anggota.daftar', compact('organisasiOptions'));
    }

    public function store(Request $request)
    {
        // Normalisasi jika input posisi dikirim sebagai 'posisi_pendaftaran' atau 'jabatan'
        if ($request->has('jabatan') && ! $request->has('posisi_pendaftaran')) {
            $rawJabatan = strtolower(trim((string) $request->input('jabatan')));
            if (in_array($rawJabatan, ['ketua', 'calon ketua'])) {
                $request->merge(['posisi_pendaftaran' => 'ketua']);
            } elseif (in_array($rawJabatan, ['wakil ketua', 'wakil_ketua', 'calon wakil ketua', 'wakil'])) {
                $request->merge(['posisi_pendaftaran' => 'wakil_ketua']);
            } elseif (in_array($rawJabatan, ['anggota', 'calon anggota', ''])) {
                $request->merge(['posisi_pendaftaran' => 'anggota']);
            }
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'organisasi' => 'required|'.Organisasi::validationRule(),
            'posisi_pendaftaran' => 'nullable|in:anggota,ketua,wakil_ketua',
            'jabatan' => 'nullable|string|in:Ketua,Wakil Ketua,Calon Ketua,Calon Wakil Ketua,Anggota',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'foto' => 'required|image|max:2048',
        ]);

        $posisi = $request->input('posisi_pendaftaran', 'anggota');
        if ($posisi === 'ketua') {
            $org = \App\Models\Organisasi::where('slug', $validated['organisasi'])->first();
            if ($org && $org->hasActiveKetua()) {
                return back()->withInput()->withErrors([
                    'posisi_pendaftaran' => 'Organisasi ini sudah memiliki ketua aktif.',
                ]);
            }
            $validated['jabatan'] = 'Calon Ketua';
        } elseif ($posisi === 'wakil_ketua') {
            $org = \App\Models\Organisasi::where('slug', $validated['organisasi'])->first();
            if ($org && $org->hasActiveWakilKetua()) {
                return back()->withInput()->withErrors([
                    'posisi_pendaftaran' => 'Organisasi ini sudah memiliki wakil ketua aktif.',
                ]);
            }
            $validated['jabatan'] = 'Calon Wakil Ketua';
        } else {
            $validated['jabatan'] = null;
        }

        $validated['posisi_pendaftaran'] = $posisi;
        $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        $validated['status'] = 'nonaktif';
        $validated['status_pendaftaran'] = 'pending';
        $validated['kode_pendaftaran'] = Anggota::buatKodePendaftaran();

        $anggota = Anggota::create($validated);

        return redirect()->route('anggota.status', $anggota->kode_pendaftaran)
            ->with('baru_daftar', true);
    }

    public function cekStatusForm()
    {
        return view('anggota.cek-status');
    }

    public function cekStatus(Request $request)
    {
        $request->validate(['kode_pendaftaran' => 'required|string']);

        $anggota = Anggota::where('kode_pendaftaran', $request->kode_pendaftaran)->first();

        if (! $anggota) {
            return back()->withErrors(['kode_pendaftaran' => 'Kode pendaftaran tidak ditemukan.'])->withInput();
        }

        return redirect()->route('anggota.status', $anggota->kode_pendaftaran);
    }

    public function showStatus($kode_pendaftaran)
    {
        $anggota = Anggota::where('kode_pendaftaran', $kode_pendaftaran)->firstOrFail();

        return view('anggota.status', compact('anggota'));
    }

    public function perbaikiForm($kode_pendaftaran)
    {
        $anggota = Anggota::where('kode_pendaftaran', $kode_pendaftaran)
            ->where('status_pendaftaran', 'rejected')
            ->firstOrFail();

        $organisasiOptions = $this->getOrganisasiOptions();

        return view('anggota.perbaiki', compact('anggota', 'organisasiOptions'));
    }

    public function perbaikiUpdate(Request $request, $kode_pendaftaran)
    {
        $anggota = Anggota::where('kode_pendaftaran', $kode_pendaftaran)
            ->where('status_pendaftaran', 'rejected')
            ->firstOrFail();

        if ($request->has('jabatan') && ! $request->has('posisi_pendaftaran')) {
            $rawJabatan = strtolower(trim((string) $request->input('jabatan')));
            if (in_array($rawJabatan, ['ketua', 'calon ketua'])) {
                $request->merge(['posisi_pendaftaran' => 'ketua']);
            } elseif (in_array($rawJabatan, ['wakil ketua', 'wakil_ketua', 'calon wakil ketua', 'wakil'])) {
                $request->merge(['posisi_pendaftaran' => 'wakil_ketua']);
            } elseif (in_array($rawJabatan, ['anggota', 'calon anggota', ''])) {
                $request->merge(['posisi_pendaftaran' => 'anggota']);
            }
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'organisasi' => 'required|'.Organisasi::validationRule(),
            'posisi_pendaftaran' => 'nullable|in:anggota,ketua,wakil_ketua',
            'jabatan' => 'nullable|string|in:Ketua,Wakil Ketua,Calon Ketua,Calon Wakil Ketua,Anggota',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|max:2048',
        ]);

        $posisi = $request->input('posisi_pendaftaran', $anggota->posisi_pendaftaran ?: 'anggota');
        if ($posisi === 'ketua') {
            $org = \App\Models\Organisasi::where('slug', $validated['organisasi'])->first();
            if ($org && $org->hasActiveKetua() && $org->ketua_id !== $anggota->id) {
                return back()->withInput()->withErrors([
                    'posisi_pendaftaran' => 'Organisasi ini sudah memiliki ketua aktif.',
                ]);
            }
            $validated['jabatan'] = 'Calon Ketua';
        } elseif ($posisi === 'wakil_ketua') {
            $org = \App\Models\Organisasi::where('slug', $validated['organisasi'])->first();
            if ($org && $org->hasActiveWakilKetua() && $org->wakil_ketua_id !== $anggota->id) {
                return back()->withInput()->withErrors([
                    'posisi_pendaftaran' => 'Organisasi ini sudah memiliki wakil ketua aktif.',
                ]);
            }
            $validated['jabatan'] = 'Calon Wakil Ketua';
        } else {
            $validated['jabatan'] = null;
        }

        $validated['posisi_pendaftaran'] = $posisi;

        if ($request->hasFile('foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('anggota', 'public');
        }

        $validated['status_pendaftaran'] = 'pending';
        $validated['alasan_penolakan'] = null;

        $anggota->update($validated);

        return redirect()->route('anggota.status', $anggota->kode_pendaftaran);
    }

    /**
     * Dapatkan opsi organisasi untuk form pendaftaran.
     */
    private function getOrganisasiOptions()
    {
        try {
            if (Schema::hasTable('organisasi')) {
                return \App\Models\Organisasi::all()->map(function ($org) {
                    return (object) [
                        'slug' => $org->slug,
                        'nama' => $org->nama,
                        'has_ketua' => $org->hasActiveKetua(),
                        'ketua_nama' => $org->getActiveKetua()?->nama,
                        'has_wakil_ketua' => $org->hasActiveWakilKetua(),
                        'wakil_ketua_nama' => $org->getActiveWakilKetua()?->nama,
                    ];
                });
            }
        } catch (\Throwable $e) {
        }

        return collect();
    }

    public function kartu($kode_pendaftaran)
    {
        $anggota = Anggota::where(function ($q) use ($kode_pendaftaran) {
            $q->where('kode_pendaftaran', $kode_pendaftaran)
                ->orWhere('id', $kode_pendaftaran)
                ->orWhere('nomor_anggota', $kode_pendaftaran);
        })
            ->where(function ($q) {
                $q->where('status_pendaftaran', 'approved')
                    ->orWhere('status', 'aktif');
            })
            ->firstOrFail();

        return view('anggota.kartu', compact('anggota'));
    }

    public function kartuPdf($kode_pendaftaran)
    {
        $anggota = Anggota::where(function ($q) use ($kode_pendaftaran) {
            $q->where('kode_pendaftaran', $kode_pendaftaran)
                ->orWhere('id', $kode_pendaftaran)
                ->orWhere('nomor_anggota', $kode_pendaftaran);
        })
            ->where(function ($q) {
                $q->where('status_pendaftaran', 'approved')
                    ->orWhere('status', 'aktif');
            })
            ->firstOrFail();

        $pdf = Pdf::loadView('anggota.kartu-pdf', compact('anggota'))
            ->setPaper([0, 0, 202, 278]);

        $nomor = $anggota->nomor_anggota ?? ($anggota->kode_pendaftaran ?? 'NU-'.$anggota->id);

        return $pdf->download('kartu-anggota-'.$nomor.'.pdf');
    }
}
