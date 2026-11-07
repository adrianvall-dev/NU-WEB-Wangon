<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\OrganisasiController as AdminOrganisasiController;
use App\Http\Controllers\Admin\PendaftaranAnggotaController as AdminPendaftaranController;
use App\Http\Controllers\Admin\PengaturanSekretariatController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\GaleriController as PublicGaleriController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PendaftaranAnggotaController;
use App\Http\Controllers\VerifikasiController;
use App\Models\Anggota;
use App\Models\KartuLayanan;
use App\Models\Kegiatan;
use App\Models\News;
use App\Models\Organisasi;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $pengaturan = Pengaturan::ambil();
    $totalAnggota = Anggota::where('status', 'aktif')->count();
    $organisasiList = collect();
    $organisasiOptions = collect();
    try {
        if (Schema::hasTable('organisasi')) {
            $organisasiList = Organisasi::orderBy('id', 'asc')->get();
            $organisasiOptions = $organisasiList->map(function ($org) {
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
    } catch (Throwable $e) {
    }

    $totalOrganisasi = $organisasiList->count();

    return view('welcome', [
        'totalNews' => News::count(),
        'publishedCount' => News::where('status', 'published')->count(),
        'draftCount' => News::where('status', 'draft')->count(),
        'newsList' => News::where('status', 'published')->latest()->take(6)->get(),
        'kegiatanList' => Kegiatan::mendatang()
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get(),
        'kegiatanTerdekat' => Kegiatan::mendatang()
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->first(),
        'galeriList' => Kegiatan::selesai()
            ->where(function ($q) {
                $q->has('fotos')
                    ->orWhereNotNull('image');
            })
            ->with(['fotos'])
            ->orderBy('event_date', 'desc')
            ->take(3)
            ->get(),
        'pengaturan' => $pengaturan,
        'tahunBerdiri' => $pengaturan->tahun_berdiri ?? 1926,
        'totalAnggota' => $totalAnggota,
        'totalAnggotaAktif' => $totalAnggota,
        'totalOrganisasi' => $totalOrganisasi,
        'organisasiList' => $organisasiList,
        'totalKegiatanSemua' => Kegiatan::count(),
        'organisasiOptions' => $organisasiOptions,
        'kartuLayananList' => Schema::hasTable('kartu_layanans')
            ? KartuLayanan::active()->orderBy('urutan', 'asc')->orderBy('id', 'asc')->get()
            : collect(),
    ]);
})->name('home');

Route::redirect('/beranda', '/');

Route::get('/berita', [NewsController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');

Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->name('kegiatan.show');

Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');

Route::get('/anggota/daftar', [PendaftaranAnggotaController::class, 'create'])->name('anggota.daftar');
Route::post('/anggota/daftar', [PendaftaranAnggotaController::class, 'store'])->name('anggota.daftar.store');
Route::get('/anggota/cek-status', [PendaftaranAnggotaController::class, 'cekStatusForm'])->name('anggota.cek-status');
Route::post('/anggota/cek-status', [PendaftaranAnggotaController::class, 'cekStatus'])->name('anggota.cek-status.submit');
Route::get('/anggota/status/{kode_pendaftaran}', [PendaftaranAnggotaController::class, 'showStatus'])->name('anggota.status');
Route::get('/anggota/perbaiki/{kode_pendaftaran}', [PendaftaranAnggotaController::class, 'perbaikiForm'])->name('anggota.perbaiki');
Route::post('/anggota/perbaiki/{kode_pendaftaran}', [PendaftaranAnggotaController::class, 'perbaikiUpdate'])->name('anggota.perbaiki.update');
Route::get('/anggota/kartu/{kode_pendaftaran}', [PendaftaranAnggotaController::class, 'kartu'])->name('anggota.kartu');
Route::get('/anggota/kartu/{kode_pendaftaran}/pdf', [PendaftaranAnggotaController::class, 'kartuPdf'])->name('anggota.kartu.pdf');

Route::get('/verifikasi/{kode}', [VerifikasiController::class, 'show'])->name('verifikasi.show');

Route::get('/galeri', [PublicGaleriController::class, 'index'])->name('galeri.index');
Route::get('/galeri/{id}', [PublicGaleriController::class, 'show'])->name('galeri.show');

/*
|--------------------------------------------------------------------------
| REDIRECT DASHBOARD (ADMIN LANGSUNG KE /admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| HALAMAN KHUSUS ADMIN anggota
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/berita', [AdminNewsController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [AdminNewsController::class, 'create'])->name('berita.create');
    Route::post('/berita', [AdminNewsController::class, 'store'])->name('berita.store');
    Route::get('/berita/{berita}/edit', [AdminNewsController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{berita}', [AdminNewsController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [AdminNewsController::class, 'destroy'])->name('berita.destroy');

    Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/create', [AdminKegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan', [AdminKegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{kegiatan}/edit', [AdminKegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    Route::get('/kelola-admin', [AdminUserController::class, 'index'])->name('admin-user.index');
    Route::get('/kelola-admin/create', [AdminUserController::class, 'create'])->name('admin-user.create');
    Route::post('/kelola-admin', [AdminUserController::class, 'store'])->name('admin-user.store');
    Route::delete('/kelola-admin/{admin}', [AdminUserController::class, 'destroy'])->name('admin-user.destroy');

    Route::resource('organisasi', AdminOrganisasiController::class);
    Route::get('/anggota', [AdminAnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/pendaftaran-anggota', [AdminPendaftaranController::class, 'index'])->name('pendaftaran-anggota.index');
    Route::get('/pendaftaran-anggota/{anggotum}', [AdminPendaftaranController::class, 'show'])->name('pendaftaran-anggota.show');
    Route::post('/pendaftaran-anggota/{anggotum}/approve', [AdminPendaftaranController::class, 'approve'])->name('pendaftaran-anggota.approve');
    Route::post('/pendaftaran-anggota/{anggotum}/reject', [AdminPendaftaranController::class, 'reject'])->name('pendaftaran-anggota.reject');
    Route::get('/pendaftaran-anggota/{anggotum}/cetak', [AdminPendaftaranController::class, 'cetakSatu'])->name('pendaftaran-anggota.cetak');
    Route::post('/pendaftaran-anggota/cetak-massal', [AdminPendaftaranController::class, 'cetakMassal'])->name('pendaftaran-anggota.cetak-massal');
    Route::get('/anggota/create', [AdminAnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AdminAnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{anggotum}/edit', [AdminAnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{anggotum}', [AdminAnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{anggotum}', [AdminAnggotaController::class, 'destroy'])->name('anggota.destroy');

    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
    Route::get('/galeri/create', [GaleriController::class, 'create'])->name('galeri.create');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{kegiatan}', [GaleriController::class, 'show'])->name('galeri.show');
    Route::post('/galeri/{kegiatan}/upload', [GaleriController::class, 'uploadFotos'])->name('galeri.upload');
    Route::post('/galeri/{kegiatan}/upload-video', [GaleriController::class, 'uploadVideo'])->name('galeri.upload-video');
    Route::delete('/galeri/foto/{foto}', [GaleriController::class, 'destroyFoto'])->name('galeri.foto.destroy');
    Route::get('/galeri/{galeri}/edit', [GaleriController::class, 'edit'])->name('galeri.edit');
    Route::put('/galeri/{galeri}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
    Route::post('/pengaturan', [GaleriController::class, 'updatePengaturan'])->name('galeri.pengaturan');

    // Pengaturan Website -> Pusat Layanan & Sekretariat
    Route::get('/pengaturan/sekretariat', [PengaturanSekretariatController::class, 'edit'])->name('pengaturan.sekretariat');
    Route::put('/pengaturan/sekretariat', [PengaturanSekretariatController::class, 'update'])->name('pengaturan.sekretariat.update');
    Route::post('/pengaturan/sekretariat/kartu', [PengaturanSekretariatController::class, 'storeCard'])->name('pengaturan.sekretariat.kartu.store');
    Route::delete('/pengaturan/sekretariat/kartu/{kartu}', [PengaturanSekretariatController::class, 'destroyCard'])->name('pengaturan.sekretariat.kartu.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTE AUTHENTICATION (dari Breeze, JANGAN DIHAPUS)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
