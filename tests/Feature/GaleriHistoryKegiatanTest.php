<?php

namespace Tests\Feature;

use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GaleriHistoryKegiatanTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_completed_activity_automatically_appears_in_admin_history_kegiatan(): void
    {
        $admin = $this->createAdmin();

        // 1. Kegiatan selesai (tanggal lampau)
        $kegiatanSelesai = Kegiatan::create([
            'title' => 'Pengajian Akbar Hari Santri',
            'kategori' => 'muslimat',
            'description' => 'Deskripsi lengkap pengajian akbar yang sangat berkesan.',
            'event_date' => now()->subDays(3)->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '11:30:00',
            'location' => 'Masjid Besar Wangon',
            'status' => 'published',
        ]);

        // 2. Kegiatan mendatang
        $kegiatanMendatang = Kegiatan::create([
            'title' => 'Rapat Kerja Mendatang',
            'kategori' => 'ansor',
            'description' => 'Rapat kerja belum terlaksana.',
            'event_date' => now()->addDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Gedung MWC',
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galeri.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengajian Akbar Hari Santri');
        $response->assertDontSee('Rapat Kerja Mendatang');
    }

    public function test_admin_can_view_history_kegiatan_detail_with_original_description(): void
    {
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Bakti Sosial Donor Darah',
            'kategori' => 'fatayat',
            'description' => 'Kegiatan donor darah bekerja sama dengan PMI Banyumas.',
            'event_date' => now()->subDay()->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Pendopo Kecamatan',
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Bakti Sosial Donor Darah');
        $response->assertSee('Kegiatan donor darah bekerja sama dengan PMI Banyumas.');
        $response->assertSee('Upload Foto Dokumentasi');
    }

    public function test_admin_can_upload_multiple_documentation_photos(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Khataman Al-Quran',
            'kategori' => 'muslimat',
            'description' => 'Khataman rutin selapanan.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '07:00:00',
            'location' => 'Ranting Klapagading',
            'status' => 'published',
        ]);

        $foto1 = UploadedFile::fake()->create('dokumentasi1.jpg', 200, 'image/jpeg');
        $foto2 = UploadedFile::fake()->create('dokumentasi2.png', 150, 'image/png');

        $response = $this->actingAs($admin)->post(route('admin.galeri.upload', $kegiatan->id), [
            'fotos' => [$foto1, $foto2],
            'caption' => 'Momen kebersamaan khataman',
        ]);

        $response->assertRedirect(route('admin.galeri.show', $kegiatan->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('foto_kegiatan', 2);
        $this->assertDatabaseHas('foto_kegiatan', [
            'kegiatan_id' => $kegiatan->id,
            'caption' => 'Momen kebersamaan khataman',
        ]);

        $uploadedPhotos = FotoKegiatan::where('kegiatan_id', $kegiatan->id)->get();
        foreach ($uploadedPhotos as $p) {
            Storage::disk('public')->assertExists($p->file_path);
        }
    }

    public function test_admin_can_delete_documentation_photo(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Pelatihan Jurnalistik',
            'kategori' => 'ansor',
            'description' => 'Pelatihan kader pers santri.',
            'event_date' => now()->subDays(4)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Aula SMK Ma\'arif',
            'status' => 'published',
        ]);

        $file = UploadedFile::fake()->create('pelatihan.jpg', 100, 'image/jpeg');
        $path = $file->store('kegiatan/galeri', 'public');

        $foto = FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => $path,
            'caption' => 'Sesi foto bersama',
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($admin)->delete(route('admin.galeri.foto.destroy', $foto->id));

        $response->assertRedirect(route('admin.galeri.show', $kegiatan->id));
        $this->assertDatabaseMissing('foto_kegiatan', ['id' => $foto->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_public_galeri_page_displays_completed_activity_and_original_description(): void
    {
        Storage::fake('public');

        $kegiatan = Kegiatan::create([
            'title' => 'Pawai Obor 1 Muharram',
            'kategori' => 'fatayat',
            'description' => 'Pawai menyambut tahun baru Islam mengelilingi desa bersama santri dan warga.',
            'event_date' => now()->subDays(7)->toDateString(),
            'start_time' => '19:30:00',
            'location' => 'Alun-alun Wangon',
            'status' => 'published',
        ]);

        $foto = UploadedFile::fake()->create('pawai.jpg', 100, 'image/jpeg');
        $path = $foto->store('kegiatan/galeri', 'public');

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => $path,
            'caption' => 'Semarak pawai obor',
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertSee('Pawai Obor 1 Muharram');
        $response->assertSee('Pawai menyambut tahun baru Islam');
        $response->assertSee(route('galeri.show', $kegiatan->id));
    }

    public function test_public_galeri_detail_page_displays_photos_and_original_info(): void
    {
        Storage::fake('public');

        $kegiatan = Kegiatan::create([
            'title' => 'Lailatul Ijtima & Istighotsah',
            'kategori' => 'muslimat',
            'description' => 'Majelis doa bersama untuk kemaslahatan umat dan bangsa.',
            'event_date' => now()->subDays(10)->toDateString(),
            'start_time' => '20:00:00',
            'location' => 'Masjid Baiturrahman',
            'status' => 'published',
        ]);

        $fotoLandscape = UploadedFile::fake()->create('landscape.jpg', 100, 'image/jpeg');
        $path1 = $fotoLandscape->store('kegiatan/galeri', 'public');

        $fotoPortrait = UploadedFile::fake()->create('portrait.jpg', 100, 'image/jpeg');
        $path2 = $fotoPortrait->store('kegiatan/galeri', 'public');

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => $path1,
            'caption' => 'Foto panorama jemaah',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => $path2,
            'caption' => 'Foto tausiyah kyai',
        ]);

        $response = $this->get(route('galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Lailatul Ijtima & Istighotsah');
        $response->assertSee('Majelis doa bersama untuk kemaslahatan umat dan bangsa.');
        $response->assertSee($fotoLandscape->hashName());
        $response->assertSee($fotoPortrait->hashName());
        $response->assertSee('Foto panorama jemaah');
        $response->assertSee('Foto tausiyah kyai');
    }

    public function test_admin_can_upload_documentation_video(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Peringatan Harlah Muslimat NU Ke-78',
            'kategori' => 'muslimat',
            'description' => 'Peringatan Harlah Muslimat NU penuh khidmat.',
            'event_date' => now()->subDays(5)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Gedung KSP Wangon',
            'status' => 'published',
        ]);

        $video = UploadedFile::fake()->create('harlah_clip.mp4', 5000, 'video/mp4');

        $response = $this->actingAs($admin)->post(route('admin.galeri.upload-video', $kegiatan->id), [
            'video' => $video,
            'caption' => 'Kilas balik harlah Muslimat',
        ]);

        $response->assertRedirect(route('admin.galeri.show', $kegiatan->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('foto_kegiatan', [
            'kegiatan_id' => $kegiatan->id,
            'tipe' => 'video',
            'caption' => 'Kilas balik harlah Muslimat',
        ]);

        $fotoRecord = FotoKegiatan::where('kegiatan_id', $kegiatan->id)->where('tipe', 'video')->first();
        $this->assertNotNull($fotoRecord);
        $this->assertTrue($fotoRecord->isVideo());
        Storage::disk('public')->assertExists($fotoRecord->file_path);
    }

    public function test_admin_can_delete_documentation_video(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Pelatihan Kepemimpinan Kader',
            'kategori' => 'ansor',
            'description' => 'Pelatihan kader militan.',
            'event_date' => now()->subDays(4)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Pondok Pesantren Al-Falah',
            'status' => 'published',
        ]);

        $videoFile = UploadedFile::fake()->create('pelatihan.mp4', 3000, 'video/mp4');
        $path = $videoFile->store('kegiatan/video', 'public');

        $videoRecord = FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => $path,
            'tipe' => 'video',
            'caption' => 'Dokumentasi pelatihan kader',
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($admin)->delete(route('admin.galeri.foto.destroy', $videoRecord->id));

        $response->assertRedirect(route('admin.galeri.show', $kegiatan->id));
        $this->assertDatabaseMissing('foto_kegiatan', ['id' => $videoRecord->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_public_galeri_detail_page_displays_video_player(): void
    {
        Storage::fake('public');

        $kegiatan = Kegiatan::create([
            'title' => 'Festival Hadroh Se-Kecamatan',
            'kategori' => 'fatayat',
            'description' => 'Festival hadroh memperebutkan piala bergilir.',
            'event_date' => now()->subDays(7)->toDateString(),
            'start_time' => '13:00:00',
            'location' => 'Aula Serbaguna',
            'status' => 'published',
        ]);

        $videoFile = UploadedFile::fake()->create('festival_performance.mp4', 4000, 'video/mp4');
        $path = $videoFile->store('kegiatan/video', 'public');

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => $path,
            'tipe' => 'video',
            'caption' => 'Penampilan grup sholawat rebana',
        ]);

        $response = $this->get(route('galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Festival Hadroh Se-Kecamatan');
        $response->assertSee('Video Dokumentasi Acara');
        $response->assertSee('Penampilan grup sholawat rebana');
        $response->assertSee('<video', false);
        $response->assertSee('controls', false);
        $response->assertSee('playsinline', false);
        $response->assertSee('preload="metadata"', false);
        $response->assertDontSee('autoplay', false);
        $response->assertSee($videoFile->hashName());
    }

    public function test_admin_can_upload_real_encoded_mp4_video(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Rutin Muslimat NU',
            'kategori' => 'muslimat',
            'description' => 'Dokumentasi pengajian rutin.',
            'event_date' => now()->subDays(1)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Masjid Wangon',
            'status' => 'published',
        ]);

        $realVideoPath = '/home/adrian/.gemini/antigravity/brain/433a2bfc-d82c-4b13-9073-e3b9d95a568f/scratch/real_dokumentasi.mp4';
        $this->assertFileExists($realVideoPath);

        // Upload the actual ffmpeg-encoded MP4 file with test mode enabled (null, true)
        $realUploadedFile = new UploadedFile(
            $realVideoPath,
            'real_dokumentasi.mp4',
            'video/mp4',
            null,
            true
        );

        $response = $this->actingAs($admin)->post(route('admin.galeri.upload-video', $kegiatan->id), [
            'video' => $realUploadedFile,
            'caption' => 'Video cuplikan nyata pengajian',
        ]);

        $response->assertRedirect(route('admin.galeri.show', $kegiatan->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('foto_kegiatan', [
            'kegiatan_id' => $kegiatan->id,
            'tipe' => 'video',
            'caption' => 'Video cuplikan nyata pengajian',
        ]);

        $videoRecord = FotoKegiatan::where('kegiatan_id', $kegiatan->id)->where('tipe', 'video')->first();
        $this->assertNotNull($videoRecord);
        Storage::disk('public')->assertExists($videoRecord->file_path);

        // Verify the public detail page can display the uploaded real video
        $publicResponse = $this->get(route('galeri.show', $kegiatan->id));
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Video cuplikan nyata pengajian');
        $publicResponse->assertSee(basename($videoRecord->file_path));
    }

    public function test_upload_video_validation_rejects_non_video_file(): void
    {
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Kegiatan Non Video',
            'kategori' => 'ansor',
            'description' => 'Kegiatan untuk uji validasi.',
            'event_date' => now()->subDays(1)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Kantor MWC',
            'status' => 'published',
        ]);

        $fakeDocument = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->post(route('admin.galeri.upload-video', $kegiatan->id), [
            'video' => $fakeDocument,
            'caption' => 'Bukan video',
        ]);

        $response->assertSessionHasErrors('video');
    }

    public function test_public_galeri_index_can_filter_by_media_type(): void
    {
        Storage::fake('public');

        // Kegiatan 1: Hanya Foto
        $kegiatanFoto = Kegiatan::create([
            'title' => 'Festival Hadroh Santri (Foto Only)',
            'kategori' => 'fatayat',
            'description' => 'Dokumentasi foto festival hadroh.',
            'event_date' => now()->subDays(5)->toDateString(),
            'start_time' => '13:00:00',
            'location' => 'Pesantren Al-Ikhlas',
            'status' => 'published',
        ]);
        FotoKegiatan::create([
            'kegiatan_id' => $kegiatanFoto->id,
            'file_path' => 'kegiatan/galeri/foto_hadroh.jpg',
            'tipe' => 'foto',
            'caption' => 'Foto Hadroh',
        ]);

        // Kegiatan 2: Hanya Video
        $kegiatanVideo = Kegiatan::create([
            'title' => 'Pengajian Rutin Muslimat (Video Only)',
            'kategori' => 'muslimat',
            'description' => 'Dokumentasi video pengajian.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Masjid Wangon',
            'status' => 'published',
        ]);
        FotoKegiatan::create([
            'kegiatan_id' => $kegiatanVideo->id,
            'file_path' => 'kegiatan/galeri/videos/video_pengajian.mp4',
            'tipe' => 'video',
            'caption' => 'Video Pengajian',
        ]);

        // 1. Filter 'video' -> Hanya menampilkan kegiatan dengan video
        $videoResponse = $this->get(route('galeri.index', ['media' => 'video']));
        $videoResponse->assertStatus(200);
        $videoResponse->assertSee('Pengajian Rutin Muslimat (Video Only)');
        $videoResponse->assertDontSee('Festival Hadroh Santri (Foto Only)');

        // 2. Filter 'foto' -> Hanya menampilkan kegiatan dengan foto
        $fotoResponse = $this->get(route('galeri.index', ['media' => 'foto']));
        $fotoResponse->assertStatus(200);
        $fotoResponse->assertSee('Festival Hadroh Santri (Foto Only)');
        $fotoResponse->assertDontSee('Pengajian Rutin Muslimat (Video Only)');

        // 3. Filter 'semua' -> Menampilkan kedua kegiatan
        $semuaResponse = $this->get(route('galeri.index', ['media' => 'semua']));
        $semuaResponse->assertStatus(200);
        $semuaResponse->assertSee('Festival Hadroh Santri (Foto Only)');
        $semuaResponse->assertSee('Pengajian Rutin Muslimat (Video Only)');
    }

    public function test_public_galeri_detail_renders_media_filter_tabs_and_handles_empty_video(): void
    {
        Storage::fake('public');

        $kegiatan = Kegiatan::create([
            'title' => 'Pelatihan Desain Grafis Kader',
            'kategori' => 'ansor',
            'description' => 'Pelatihan di bidang desain grafis.',
            'event_date' => now()->subDays(3)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Gedung Muslimat NU',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/desain.jpg',
            'tipe' => 'foto',
            'caption' => 'Karya Santriwati',
        ]);

        $response = $this->get(route('galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Pelatihan Desain Grafis Kader');
        // Filter tabs
        $response->assertSee('tabMediaSemua', false);
        $response->assertSee('tabMediaFoto', false);
        $response->assertSee('tabMediaVideo', false);
        // Photo is present
        $response->assertSee('Karya Santriwati');
        // Empty video state is available in DOM for tab switching
        $response->assertSee('Belum Ada Video Dokumentasi');
    }

    public function test_admin_history_kegiatan_page_does_not_contain_media_filters(): void
    {
        $admin = $this->createAdmin();

        Kegiatan::create([
            'title' => 'Pengajian Muslimat Rutin',
            'kategori' => 'muslimat',
            'description' => 'Deskripsi kegiatan.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Masjid Wangon',
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galeri.index'));

        $response->assertStatus(200);
        // Halaman pemilihan history kegiatan di admin tidak memiliki filter media Foto/Video
        $response->assertDontSee('tabMediaSemua');
        $response->assertDontSee('tabMediaFoto');
        $response->assertDontSee('tabMediaVideo');
    }
}
