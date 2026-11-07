<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Support\Organisasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Hitung Statistik Real-time hanya untuk Anggota Aktif
        $stats = [
            'total' => Anggota::where('status', 'aktif')->count(),
            'ansor' => Anggota::where('status', 'aktif')->where('organisasi', 'ansor')->count(),
            'fatayat' => Anggota::where('status', 'aktif')->where('organisasi', 'fatayat')->count(),
            'muslimat' => Anggota::where('status', 'aktif')->where('organisasi', 'muslimat')->count(),
            'ipnu' => Anggota::where('status', 'aktif')->where('organisasi', 'ipnu')->count(),
            'ippnu' => Anggota::where('status', 'aktif')->where('organisasi', 'ippnu')->count(),
            'pagar_nusa' => Anggota::where('status', 'aktif')->where('organisasi', 'pagar_nusa')->count(),
        ];

        // 2. Query Utama Anggota Aktif
        $query = Anggota::where('status', 'aktif');

        // Filter Organisasi jika dipilih
        $currentOrganisasi = null;
        if ($request->filled('organisasi') && in_array($request->organisasi, Organisasi::all())) {
            $query->where('organisasi', $request->organisasi);
            $currentOrganisasi = \App\Models\Organisasi::with(['ketua', 'wakilKetua'])
                ->where('slug', $request->organisasi)
                ->first();
        }

        // Filter Pencarian (Nama / ID Anggota / NIK / Jabatan)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('nomor_anggota', 'like', "%{$search}%")
                    ->orWhere('kode_pendaftaran', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }

                // Jika di tabel anggota ada kolom nik, ikut dicari
                if (\Schema::hasColumn('anggota', 'nik')) {
                    $q->orWhere('nik', 'like', "%{$search}%");
                }
            });
        }

        // Ambil Data Paginasi (8 item per halaman sesuai desain grid 4x2)
        $anggotaList = $query->orderBy('nama', 'asc')->paginate(8)->withQueryString();

        return view('anggota.index', [
            'anggotaList' => $anggotaList,
            'stats' => $stats,
            'currentOrganisasi' => $currentOrganisasi,
        ]);
    }

    /**
     * Tampilan pratinjau kartu di halaman web
     */
    public function kartu($kode)
    {
        $anggota = Anggota::where('kode_pendaftaran', $kode)
            ->orWhere('id', $kode)
            ->firstOrFail();

        return view('anggota.kartu', compact('anggota'));
    }

    /**
     * Download otomatis PDF Kartu Anggota (Ukuran KTP/ID Card)
     */
    public function kartuPdf($kode)
    {
        $anggota = Anggota::where('kode_pendaftaran', $kode)
            ->orWhere('id', $kode)
            ->firstOrFail();

        // Mengatur ukuran kertas ID Card presisi portrait -> [0, 0, 202, 278] pt
        $pdf = Pdf::loadView('anggota.kartu-pdf', compact('anggota'))
            ->setPaper([0, 0, 202, 278]);

        $filename = 'kartu-anggota-'.($anggota->nomor_anggota ?? 'NU-'.$anggota->id).'.pdf';

        return $pdf->download($filename);
    }
}
