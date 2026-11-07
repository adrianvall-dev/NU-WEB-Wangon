<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\News;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionHeadingsTextAnimateTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_renders_text_animate_engine_and_section_headings(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Assert TextAnimate Engine assets are loaded
        $response->assertSee('initTextAnimate', false);
        $response->assertSee('text-animate-sr-only', false);
        $response->assertSee('prefers-reduced-motion', false);
        $response->assertSee('[data-text-animate="blurInUp"]', false);

        // Assert Welcome page headings have data-text-animate
        $response->assertSee('id="hero-title" data-text-animate="blurInUp"', false);
        $response->assertSee('id="tentang-heading" data-text-animate="blurInUp"', false);
        $response->assertSee('Kabar, Aspirasi, dan Syiar NU Wangon', false);
        $response->assertSee('Dokumentasi Khidmah &amp; Aksi Nyata', false);
        $response->assertSee('Bergabung &amp; Berkhidmah Bersama NU Wangon', false);
        $response->assertSee('Kantor MWC Nahdlatul Ulama Wangon', false);
    }

    public function test_kegiatan_pages_render_text_animate_headings(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Akbar Hari Santri Wangon',
            'kategori' => 'ansor',
            'description' => 'Pengajian akbar dalam rangka memperingati Hari Santri.',
            'event_date' => now()->addDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Masjid Besar Wangon',
            'status' => 'published',
        ]);

        // 1. Index page
        $resIndex = $this->get(route('kegiatan.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('data-text-animate="blurInUp"', false);
        $resIndex->assertSee('Agenda &amp; Kegiatan', false);

        // 2. Show page
        $resShow = $this->get(route('kegiatan.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('data-text-animate="blurInUp"', false);
        $resShow->assertSee($kegiatan->title, false);
    }

    public function test_galeri_pages_render_text_animate_headings(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Dokumentasi Apel Banser Siaga',
            'kategori' => 'ansor',
            'description' => 'Dokumentasi apel kesiapsiagaan kader Banser.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '07:30:00',
            'location' => 'Lapangan Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/apel.jpg',
            'tipe' => 'foto',
            'caption' => 'Barisan pasukan Banser',
        ]);

        // 1. Index page
        $resIndex = $this->get(route('galeri.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('data-text-animate="blurInUp"', false);
        $resIndex->assertSee('Galeri Dokumentasi', false);

        // 2. Show page
        $resShow = $this->get(route('galeri.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('data-text-animate="blurInUp"', false);
        $resShow->assertSee($kegiatan->title, false);
    }

    public function test_berita_pages_render_text_animate_headings(): void
    {
        $user = User::factory()->create();

        $berita = News::create([
            'title' => 'Pembangunan Balai Pertemuan MWC NU Tahap Dua',
            'slug' => 'pembangunan-balai-pertemuan-mwc-nu-tahap-dua',
            'content' => 'Pelaksanaan pembangunan balai pertemuan tahap kedua telah resmi dimulai.',
            'kategori' => 'ansor',
            'user_id' => $user->id,
            'published_at' => now(),
            'status' => 'published',
        ]);

        // 1. Index page
        $resIndex = $this->get(route('berita.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('data-text-animate="blurInUp"', false);
        $resIndex->assertSee('Warta &amp; Informasi', false);

        // 2. Show page
        $resShow = $this->get(route('berita.show', $berita->slug));
        $resShow->assertStatus(200);
        $resShow->assertSee('data-text-animate="blurInUp"', false);
        $resShow->assertSee($berita->title, false);
    }

    public function test_anggota_pages_render_text_animate_headings(): void
    {
        $org = Organisasi::firstOrCreate(
            ['slug' => 'ansor'],
            ['nama' => 'Gerakan Pemuda Ansor', 'singkatan' => 'GP Ansor', 'urutan' => 1]
        );

        $anggota = Anggota::create([
            'nama' => 'Gus Muhammad Ridwan',
            'nik' => '3302011234560001',
            'organisasi_id' => $org->id,
            'organisasi' => 'ansor',
            'nomor_anggota' => 'KARTANU-001-9988',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'nomor_pendaftaran' => 'REG-12345678',
        ]);

        // 1. Anggota Index
        $resIndex = $this->get(route('anggota.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('data-text-animate="blurInUp"', false);
        $resIndex->assertSee('Direktori Anggota', false);

        // 2. Pendaftaran
        $resDaftar = $this->get(route('anggota.daftar'));
        $resDaftar->assertStatus(200);
        $resDaftar->assertSee('data-text-animate="blurInUp"', false);
        $resDaftar->assertSee('Bergabung', false);

        // 3. Cek Status
        $resCek = $this->get(route('anggota.cek-status'));
        $resCek->assertStatus(200);
        $resCek->assertSee('data-text-animate="blurInUp"', false);
        $resCek->assertSee('Cek Status Pendaftaran', false);

        // 4. Verifikasi QR
        $resVerif = $this->get(route('verifikasi.show', ['kode' => $anggota->nomor_anggota]));
        $resVerif->assertStatus(200);
        $resVerif->assertSee('data-text-animate="blurInUp"', false);
        $resVerif->assertSee('Verifikasi Keaslian Kartu', false);
    }
}
