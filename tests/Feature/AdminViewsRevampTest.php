<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\News;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminViewsRevampTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_all_admin_views_render_successfully_with_solid_grotesque_system(): void
    {
        $admin = $this->createAdmin();

        // Seed sample models for testing views
        $news = News::create([
            'title' => 'Berita Pelatihan Digital Kader Sindang',
            'slug' => 'berita-pelatihan-digital-kader-sindang',
            'kategori' => 'muslimat',
            'content' => 'Konten pelatihan digitalisasi arsip dan data.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $kegiatan = Kegiatan::create([
            'title' => 'Rapat Pleno MWC NU Sindang',
            'kategori' => 'muslimat',
            'status' => 'published',
            'event_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'location' => 'Kantor MWC Sindang',
            'description' => 'Pembahasan agenda triwulan.',
            'latitude' => -7.53,
            'longitude' => 109.05,
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'galeri/sample.jpg',
            'tipe' => 'foto',
            'caption' => 'Foto Rapat Pleno',
        ]);

        $org = Organisasi::updateOrCreate(['slug' => 'fatayat'], [
            'nama' => 'Fatayat NU Sindang',
            'singkatan' => 'Fatayat',
            'deskripsi' => 'Badan otonom pemudi NU.',
            'visi' => 'Terwujudnya pemudi yang mandiri.',
            'misi' => ['Kaderisasi', 'Pemberdayaan ekonomi'],
        ]);

        $anggota = Anggota::factory()->create([
            'nama' => 'Nur Fatimah',
            'organisasi' => 'fatayat',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'jabatan' => 'Ketua PAC',
        ]);

        $calonAnggota = Anggota::factory()->create([
            'nama' => 'Dewi Sartika',
            'organisasi' => 'fatayat',
            'status' => 'nonaktif',
            'status_pendaftaran' => 'pending',
            'jabatan' => 'Calon Anggota',
        ]);

        // 1. Dashboard
        $dashRes = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Warta &amp; Publikasi', false);
        $dashRes->assertSee('Agenda Kegiatan');
        $dashRes->assertSee('Rasio Publikasi');
        $dashRes->assertSee('Tasks for today');
        $dashRes->assertSee('Statistics');
        $dashRes->assertSee('Lihat Website');

        // 2. Berita CRUD views
        $beritaIndex = $this->actingAs($admin)->get(route('admin.berita.index'));
        $beritaIndex->assertStatus(200);
        $beritaIndex->assertSee('Berita Pelatihan Digital Kader Sindang');

        $beritaCreate = $this->actingAs($admin)->get(route('admin.berita.create'));
        $beritaCreate->assertStatus(200);
        $beritaCreate->assertSee('Tambah Berita Baru');

        $beritaEdit = $this->actingAs($admin)->get(route('admin.berita.edit', $news->id));
        $beritaEdit->assertStatus(200);
        $beritaEdit->assertSee('Edit Warta &amp; Artikel', false);

        // 3. Kegiatan CRUD views
        $kegiatanIndex = $this->actingAs($admin)->get(route('admin.kegiatan.index'));
        $kegiatanIndex->assertStatus(200);
        $kegiatanIndex->assertSee('Rapat Pleno MWC NU Sindang');

        $kegiatanCreate = $this->actingAs($admin)->get(route('admin.kegiatan.create'));
        $kegiatanCreate->assertStatus(200);
        $kegiatanCreate->assertSee('Tambah Kegiatan Baru');

        $kegiatanEdit = $this->actingAs($admin)->get(route('admin.kegiatan.edit', $kegiatan->id));
        $kegiatanEdit->assertStatus(200);
        $kegiatanEdit->assertSee('Edit Agenda Kegiatan');

        // 4. Galeri views
        $galeriIndex = $this->actingAs($admin)->get(route('admin.galeri.index'));
        $galeriIndex->assertStatus(200);
        $galeriIndex->assertSee('Galeri &amp; History Kegiatan', false);

        $galeriShow = $this->actingAs($admin)->get(route('admin.galeri.show', $kegiatan->id));
        $galeriShow->assertStatus(200);
        $galeriShow->assertSee('Rapat Pleno MWC NU Sindang');
        $galeriShow->assertSee('Foto Rapat Pleno');

        // 5. Anggota CRUD views
        $anggotaIndex = $this->actingAs($admin)->get(route('admin.anggota.index'));
        $anggotaIndex->assertStatus(200);
        $anggotaIndex->assertSee('Nur Fatimah');

        $anggotaCreate = $this->actingAs($admin)->get(route('admin.anggota.create'));
        $anggotaCreate->assertStatus(200);
        $anggotaCreate->assertSee('Tambah Anggota Baru');

        $anggotaEdit = $this->actingAs($admin)->get(route('admin.anggota.edit', $anggota->id));
        $anggotaEdit->assertStatus(200);
        $anggotaEdit->assertSee('Edit Anggota: Nur Fatimah');

        // 6. Pendaftaran Anggota views
        $pendaftaranIndex = $this->actingAs($admin)->get(route('admin.pendaftaran-anggota.index'));
        $pendaftaranIndex->assertStatus(200);
        $pendaftaranIndex->assertSee('Dewi Sartika');

        $pendaftaranShow = $this->actingAs($admin)->get(route('admin.pendaftaran-anggota.show', $calonAnggota->id));
        $pendaftaranShow->assertStatus(200);
        $pendaftaranShow->assertSee('Detail Berkas Pendaftaran');

        // 7. Organisasi CRUD views
        $orgIndex = $this->actingAs($admin)->get(route('admin.organisasi.index'));
        $orgIndex->assertStatus(200);
        $orgIndex->assertSee('Fatayat NU Sindang');

        $orgCreate = $this->actingAs($admin)->get(route('admin.organisasi.create'));
        $orgCreate->assertStatus(200);
        $orgCreate->assertSee('Tambah Badan Otonom Baru');

        $orgShow = $this->actingAs($admin)->get(route('admin.organisasi.show', $org->id));
        $orgShow->assertStatus(200);
        $orgShow->assertSee('Fatayat NU Sindang');

        $orgEdit = $this->actingAs($admin)->get(route('admin.organisasi.edit', $org->id));
        $orgEdit->assertStatus(200);
        $orgEdit->assertSee('Fatayat NU Sindang');

        // 8. Admin User views
        $adminUserIndex = $this->actingAs($admin)->get(route('admin.admin-user.index'));
        $adminUserIndex->assertStatus(200);
        $adminUserIndex->assertSee('Kelola Akun Admin');

        $adminUserCreate = $this->actingAs($admin)->get(route('admin.admin-user.create'));
        $adminUserCreate->assertStatus(200);
        $adminUserCreate->assertSee('Tambah Admin Baru');
    }

    public function test_admin_anggota_pagination_and_stat_cards_render_correctly(): void
    {
        $admin = $this->createAdmin();

        // Create 11 anggota to trigger pagination (10 per page)
        Anggota::factory()->count(11)->create([
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);

        // Request page 1
        $response = $this->actingAs($admin)->get(route('admin.anggota.index'));
        $response->assertStatus(200);

        // Verify stat-grid-4 exists
        $response->assertSee('stat-grid-4');

        // Verify pagination summary for page 1
        $response->assertSee('Menampilkan', false);
        $response->assertSee('<span class="text-dark fw-extrabold">1</span>&ndash;<span class="text-dark fw-extrabold">10</span>', false);
        $response->assertSee('dari <span class="text-dark fw-extrabold">11</span> anggota', false);

        // Verify Previous is disabled on page 1
        $response->assertSee('opacity-50 pe-none', false);

        // Verify Page 2 link is present
        $response->assertSee('page=2');

        // Request page 2
        $responsePage2 = $this->actingAs($admin)->get(route('admin.anggota.index', ['page' => 2]));
        $responsePage2->assertStatus(200);

        // Verify pagination summary for page 2
        $responsePage2->assertSee('<span class="text-dark fw-extrabold">11</span>&ndash;<span class="text-dark fw-extrabold">11</span>', false);
        $responsePage2->assertSee('dari <span class="text-dark fw-extrabold">11</span> anggota', false);
    }

    public function test_dashboard_galeri_renders_with_excerpt_and_detail_link(): void
    {
        $admin = $this->createAdmin();

        $longDescription = 'Ini adalah deskripsi kegiatan yang sangat panjang sekali untuk memastikan bahwa sistem dashboard admin memotong teks menggunakan excerpt Str::limit dan tidak menampilkan keseluruhan teks yang bisa merusak keseragaman layout.';

        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Akbar Selesai',
            'kategori' => 'fatayat',
            'status' => 'published',
            'event_date' => now()->subDays(2)->format('Y-m-d'),
            'start_time' => '08:00:00',
            'end_time' => '11:00:00',
            'location' => 'Masjid Besar',
            'description' => $longDescription,
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'galeri/test.jpg',
            'tipe' => 'foto',
            'caption' => 'Foto Dokumentasi Pengajian',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Verify Galeri & Dokumentasi header
        $response->assertSee('Galeri &amp; Dokumentasi', false);
        $response->assertSee('Pengajian Akbar Selesai');

        // Truncated excerpt is present
        $excerpt = Str::limit($longDescription, 85);
        $response->assertSee($excerpt);

        // Verify "Lihat Detail" link with route admin.galeri.show
        $response->assertSee(route('admin.galeri.show', $kegiatan->id));
        $response->assertSee('Lihat Detail');
    }
}
