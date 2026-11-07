<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesRevampTest extends TestCase
{
    use RefreshDatabase;

    public function test_agenda_index_and_show_render_successfully(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Rutin Ahad Pagi',
            'kategori' => 'muslimat',
            'status' => 'published',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00:00',
            'end_time' => '11:00:00',
            'location' => 'Gedung MWC NU Wangon',
            'description' => 'Pengajian rutin bersama para alim ulama se-Kecamatan Wangon.',
            'latitude' => -7.531234,
            'longitude' => 109.054321,
        ]);

        $resIndex = $this->get(route('kegiatan.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Pengajian Rutin Ahad Pagi');
        $resIndex->assertSee('Agenda &amp; Kegiatan', false);
        $resIndex->assertSee('Semua Agenda');

        $resShow = $this->get(route('kegiatan.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('Pengajian Rutin Ahad Pagi');
        $resShow->assertSee('id="countdown-card"', false);
        $resShow->assertSee('id="publicKegiatanMap"', false);
        $resShow->assertSee('Gedung MWC NU Wangon');
    }

    public function test_galeri_index_and_show_render_with_photo_and_video(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Peringatan Harlah NU ke-101',
            'kategori' => 'muslimat',
            'status' => 'published',
            'event_date' => now()->subDays(3)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'location' => 'Alun-alun Wangon',
            'description' => 'Dokumentasi resepsi harlah NU ke-101.',
        ]);

        // Buat 1 foto dan 1 video
        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'galeri/foto_sample.jpg',
            'tipe' => 'foto',
            'caption' => 'Foto Pembukaan Acara',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'galeri/video_sample.mp4',
            'tipe' => 'video',
            'caption' => 'Video Kirab Santri',
        ]);

        $resIndex = $this->get(route('galeri.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Peringatan Harlah NU ke-101');
        $resIndex->assertSee('Semua Media');
        $resIndex->assertSee('Hanya Foto');
        $resIndex->assertSee('Hanya Video');

        $resShow = $this->get(route('galeri.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('Peringatan Harlah NU ke-101');
        $resShow->assertSee('tabMediaSemua');
        $resShow->assertSee('tabMediaFoto');
        $resShow->assertSee('tabMediaVideo');
        $resShow->assertSee('id="videoContainer"', false);
        $resShow->assertSee('id="photoContainer"', false);
        $resShow->assertSee('Video Kirab Santri');
        $resShow->assertSee('Foto Pembukaan Acara');
        $resShow->assertSee('id="photoLightbox"', false);
    }

    public function test_anggota_index_renders_with_database_records_and_modals(): void
    {
        $anggota = Anggota::factory()->create([
            'nama' => 'Ahmad Fauzi',
            'organisasi' => 'ansor',
            'jabatan' => 'Ketua PAC GP Ansor',
            'status' => 'aktif',
            'no_hp' => '081234567890',
            'alamat' => 'Desa Klapagading RT 02 RW 01',
        ]);

        $res = $this->get(route('anggota.index'));
        $res->assertStatus(200);
        $res->assertSee('Ahmad Fauzi');
        $res->assertSee('Ketua PAC GP Ansor');
        $res->assertSee('GP ANSOR');
        $res->assertSee('modalProfil'.$anggota->id);
        $res->assertSee('Daftar Anggota (KARTANU)');
    }

    public function test_berita_index_and_show_render_successfully(): void
    {
        $news = News::create([
            'title' => 'Pelantikan Serentak Pengurus Ranting NU se-Wangon',
            'slug' => 'pelantikan-serentak-pengurus-ranting-nu-se-wangon',
            'kategori' => 'muslimat',
            'content' => 'Pelantikan berlangsung khidmat dihadiri oleh ratusan kader Nahdliyin.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $resIndex = $this->get(route('berita.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Pelantikan Serentak Pengurus Ranting NU se-Wangon');
        $resIndex->assertSee('Warta &amp; Informasi', false);

        $resShow = $this->get(route('berita.show', $news->slug));
        $resShow->assertStatus(200);
        $resShow->assertSee('Pelantikan Serentak Pengurus Ranting NU se-Wangon');
        $resShow->assertSee('Pelantikan berlangsung khidmat dihadiri oleh ratusan kader Nahdliyin.');
        $resShow->assertSee('Informasi Warta');
        $resShow->assertSee('Bagikan Berita');
    }

    public function test_registration_and_status_pages_render_successfully(): void
    {
        $resDaftar = $this->get(route('anggota.daftar'));
        $resDaftar->assertStatus(200);
        $resDaftar->assertSee('PENDAFTARAN ANGGOTA');
        $resDaftar->assertSee('Nama Lengkap');

        $resCekStatus = $this->get(route('anggota.cek-status'));
        $resCekStatus->assertStatus(200);
        $resCekStatus->assertSee('Cek Status Pendaftaran');

        $anggotaPending = Anggota::factory()->create([
            'nama' => 'Siti Khadijah',
            'kode_pendaftaran' => 'REG-TEST1234',
            'status_pendaftaran' => 'pending',
            'organisasi' => 'fatayat',
        ]);

        $resStatus = $this->get(route('anggota.status', $anggotaPending->kode_pendaftaran));
        $resStatus->assertStatus(200);
        $resStatus->assertSee('Menunggu Verifikasi Admin');
        $resStatus->assertSee('REG-TEST1234');
    }

    public function test_verifikasi_and_kartu_pages_render_successfully(): void
    {
        $anggota = Anggota::factory()->create([
            'nama' => 'H. M. Syukron',
            'organisasi' => 'muslimat',
            'nomor_anggota' => 'NU-MUS-2026-0001',
            'kode_pendaftaran' => 'REG-SYUKRON01',
            'kode_verifikasi' => 'VERIF-SYUKRON01',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);

        $resKartu = $this->get(route('anggota.kartu', $anggota->kode_pendaftaran));
        $resKartu->assertStatus(200);
        $resKartu->assertSee('H. M. Syukron');
        $resKartu->assertSee('Putar Kartu (3D Flip)');

        $resVerif = $this->get(route('verifikasi.show', $anggota->kode_verifikasi));
        $resVerif->assertStatus(200);
        $resVerif->assertSee('H. M. Syukron');
        $resVerif->assertSee('Anggota Sah');
    }
}
