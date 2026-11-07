<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumberTickerStatisticalAnimationTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_renders_number_ticker_engine_and_metrics(): void
    {
        $org = Organisasi::firstOrCreate(
            ['slug' => 'ansor'],
            ['nama' => 'Gerakan Pemuda Ansor', 'singkatan' => 'GP Ansor', 'urutan' => 1]
        );

        Anggota::create([
            'nama' => 'Ahmad Wahid',
            'nik' => '3302011234560002',
            'organisasi_id' => $org->id,
            'organisasi' => 'ansor',
            'nomor_anggota' => 'KARTANU-001-1001',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'nomor_pendaftaran' => 'REG-00000001',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Engine & CSS Assets
        $response->assertSee('initNumberTickers', false);
        $response->assertSee('number-ticker-sr-only', false);
        $response->assertSee('font-variant-numeric: tabular-nums', false);
        $response->assertSee('prefers-reduced-motion', false);

        // 2. Stat counter cards
        $response->assertSee('data-number-ticker="1"', false); // totalAnggota = 1
        $response->assertSee('data-number-ticker="', false);
    }

    public function test_anggota_index_renders_number_ticker_on_all_metrics(): void
    {
        $orgAnsor = Organisasi::firstOrCreate(
            ['slug' => 'ansor'],
            ['nama' => 'Gerakan Pemuda Ansor', 'singkatan' => 'GP Ansor', 'urutan' => 1]
        );

        $orgFatayat = Organisasi::firstOrCreate(
            ['slug' => 'fatayat'],
            ['nama' => 'Fatayat NU', 'singkatan' => 'Fatayat', 'urutan' => 2]
        );

        Anggota::create([
            'nama' => 'Kader Ansor 1',
            'nik' => '3302011234560010',
            'organisasi_id' => $orgAnsor->id,
            'organisasi' => 'ansor',
            'nomor_anggota' => 'KARTANU-001-1010',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'nomor_pendaftaran' => 'REG-00000010',
        ]);

        Anggota::create([
            'nama' => 'Kader Fatayat 1',
            'nik' => '3302011234560020',
            'organisasi_id' => $orgFatayat->id,
            'organisasi' => 'fatayat',
            'nomor_anggota' => 'KARTANU-002-1020',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'nomor_pendaftaran' => 'REG-00000020',
        ]);

        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);

        // Hero total count metric
        $response->assertSee('data-number-ticker="2"', false); // Total 2 kader
        $response->assertSee('Kader Terdata', false);

        // Banom summary cards
        $response->assertSee('Filter GP ANSOR', false);
        $response->assertSee('Filter FATAYAT NU', false);
        $response->assertSee('data-number-ticker="1"', false); // ansor = 1, fatayat = 1
        $response->assertSee('data-number-ticker="0"', false); // other banoms = 0
    }

    public function test_galeri_pages_render_number_ticker_on_media_counts(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Peringatan Hari Santri Nasional',
            'kategori' => 'ansor',
            'description' => 'Dokumentasi kegiatan hari santri nasional di Wangon.',
            'event_date' => now()->subDays(3)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Alun-alun Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/foto1.jpg',
            'tipe' => 'foto',
            'caption' => 'Apel akbar',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/video1.mp4',
            'tipe' => 'video',
            'caption' => 'Video cuplikan pembukaan',
        ]);

        // 1. Galeri Index
        $resIndex = $this->get(route('galeri.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('data-number-ticker="1"', false);
        $resIndex->assertSee('Foto', false);
        $resIndex->assertSee('Video', false);

        // 2. Galeri Show
        $resShow = $this->get(route('galeri.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('data-number-ticker="1"', false);
        $resShow->assertSee('Video Dokumentasi', false);
        $resShow->assertSee('Foto Dokumentasi', false);
        $resShow->assertSee('Semua Media', false);
    }

    public function test_sensitive_identifiers_and_dates_are_not_given_number_ticker(): void
    {
        $org = Organisasi::firstOrCreate(
            ['slug' => 'ansor'],
            ['nama' => 'Gerakan Pemuda Ansor', 'singkatan' => 'GP Ansor', 'urutan' => 1]
        );

        $anggota = Anggota::create([
            'nama' => 'Ahmad Mustofa',
            'nik' => '3302011234567890',
            'organisasi_id' => $org->id,
            'organisasi' => 'ansor',
            'nomor_anggota' => 'KARTANU-001-9988',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'nomor_pendaftaran' => 'REG-12345678',
        ]);

        // Verifikasi page
        $resVerif = $this->get(route('verifikasi.show', ['kode' => $anggota->nomor_anggota]));
        $resVerif->assertStatus(200);
        $resVerif->assertDontSee('data-number-ticker="'.$anggota->nomor_anggota.'"', false);
        $resVerif->assertDontSee('data-number-ticker="'.$anggota->nik.'"', false);
    }
}
