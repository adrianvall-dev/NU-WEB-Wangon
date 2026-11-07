<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Organisasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PendaftaranAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $posisi = $request->get('posisi');

        $query = Anggota::whereNotNull('kode_pendaftaran');

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status_pendaftaran', $status);
        }

        if (in_array($posisi, ['ketua', 'wakil_ketua', 'anggota'])) {
            if ($posisi === 'anggota') {
                $query->where(function ($q) {
                    $q->where('posisi_pendaftaran', 'anggota')->orWhereNull('posisi_pendaftaran');
                });
            } else {
                $query->where('posisi_pendaftaran', $posisi);
            }
        }

        $pendaftaranList = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Data statistik
        $counts = [
            'total' => Anggota::whereNotNull('kode_pendaftaran')->count(),
            'pending' => Anggota::whereNotNull('kode_pendaftaran')->where('status_pendaftaran', 'pending')->count(),
            'approved' => Anggota::whereNotNull('kode_pendaftaran')->where('status_pendaftaran', 'approved')->count(),
            'rejected' => Anggota::whereNotNull('kode_pendaftaran')->where('status_pendaftaran', 'rejected')->count(),
            'ketua' => Anggota::whereNotNull('kode_pendaftaran')->where('posisi_pendaftaran', 'ketua')->count(),
            'wakil_ketua' => Anggota::whereNotNull('kode_pendaftaran')->where('posisi_pendaftaran', 'wakil_ketua')->count(),
            'anggota' => Anggota::whereNotNull('kode_pendaftaran')->where(function ($q) {
                $q->where('posisi_pendaftaran', 'anggota')->orWhereNull('posisi_pendaftaran');
            })->count(),
        ];

        return view('admin.pendaftaran.index', compact('pendaftaranList', 'status', 'posisi', 'counts'));
    }

    public function show(Anggota $anggotum)
    {
        return view('admin.pendaftaran.show', ['anggota' => $anggotum]);
    }

    public function approve(Anggota $anggotum)
    {
        $updateData = [
            'status_pendaftaran' => 'approved',
            'status' => 'aktif',
            'nomor_anggota' => $anggotum->nomor_anggota ?? Anggota::buatNomorAnggota($anggotum->organisasi),
            'kode_verifikasi' => $anggotum->kode_verifikasi ?? Anggota::buatKodeVerifikasi(),
            'alasan_penolakan' => null,
        ];

        if ($anggotum->posisi_pendaftaran === 'ketua') {
            $updateData['jabatan'] = 'Ketua';
        } elseif ($anggotum->posisi_pendaftaran === 'wakil_ketua') {
            $updateData['jabatan'] = 'Wakil Ketua';
        }

        $anggotum->update($updateData);

        if ($anggotum->posisi_pendaftaran === 'ketua' && $anggotum->organisasi) {
            $org = Organisasi::where('slug', $anggotum->organisasi)->first();
            if ($org) {
                if ($org->ketua_id && $org->ketua_id !== $anggotum->id) {
                    Anggota::where('id', $org->ketua_id)->update(['jabatan' => 'Anggota']);
                }
                $org->update(['ketua_id' => $anggotum->id]);
            }
        } elseif ($anggotum->posisi_pendaftaran === 'wakil_ketua' && $anggotum->organisasi) {
            $org = Organisasi::where('slug', $anggotum->organisasi)->first();
            if ($org) {
                if ($org->wakil_ketua_id && $org->wakil_ketua_id !== $anggotum->id) {
                    Anggota::where('id', $org->wakil_ketua_id)->update(['jabatan' => 'Anggota']);
                }
                $org->update(['wakil_ketua_id' => $anggotum->id]);
            }
        }

        return redirect()->route('admin.pendaftaran-anggota.index')
            ->with('success', 'Pendaftaran disetujui. Nomor anggota: '.$anggotum->nomor_anggota);
    }

    public function reject(Request $request, Anggota $anggotum)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $anggotum->update([
            'status_pendaftaran' => 'rejected',
            'status' => 'nonaktif',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        return redirect()->route('admin.pendaftaran-anggota.index')->with('success', 'Pendaftaran ditolak.');
    }

    public function cetakSatu(Anggota $anggotum)
    {
        abort_unless($anggotum->status_pendaftaran === 'approved', 404);

        $pdf = Pdf::loadView('anggota.kartu-pdf', ['anggota' => $anggotum])
            ->setPaper([0, 0, 202, 278]);

        return $pdf->download('kartu-anggota-'.$anggotum->nomor_anggota.'.pdf');
    }

    public function cetakMassal(Request $request)
    {
        $ids = $request->input('ids', []);

        $anggotaList = Anggota::whereIn('id', $ids)
            ->where('status_pendaftaran', 'approved')
            ->get();

        $pdf = Pdf::loadView('anggota.kartu-pdf-massal', compact('anggotaList'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('kartu-anggota-massal.pdf');
    }
}
