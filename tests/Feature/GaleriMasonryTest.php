<?php

namespace Tests\Feature;

use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriMasonryTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_history_kegiatan_renders_masonry_photo_showcase_with_database_photos(): void
    {
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Festival Hadroh dan Shalawat Jawa',
            'kategori' => 'ansor',
            'description' => 'Gelar pentas seni dan kreasi hadroh santri se-Wangon.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '19:30:00',
            'location' => 'Pendopo Wangon',
            'status' => 'published',
        ]);

        $foto1 = FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/festival_hadroh_1.jpg',
            'tipe' => 'foto',
            'caption' => 'Penampilan grup hadroh santri putra',
        ]);

        $foto2 = FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/festival_hadroh_2.jpg',
            'tipe' => 'foto',
            'caption' => 'Sambutan ketua panitia hadroh',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galeri.show', $kegiatan->id));

        $response->assertStatus(200);

        // Verify Masonry container & scoped elements
        $response->assertSee('history-photo-masonry', false);
        $response->assertSee('history-photo-masonry__list', false);
        $response->assertSee('history-photo-masonry__item', false);
        $response->assertSee('history-photo-masonry__card', false);
        $response->assertSee('history-photo-masonry__media', false);
        $response->assertSee('history-photo-masonry__img', false);
        $response->assertSee('history-photo-masonry__actions', false);
        $response->assertSee('history-photo-masonry__btn-preview', false);
        $response->assertSee('history-photo-masonry__btn-delete', false);
        $response->assertSee('history-photo-masonry__footer', false);

        // Verify database photos are loaded
        $response->assertSee('festival_hadroh_1.jpg');
        $response->assertSee('festival_hadroh_2.jpg');
        $response->assertSee('Penampilan grup hadroh santri putra');
        $response->assertSee('Sambutan ketua panitia hadroh');

        // Verify delete actions
        $response->assertSee(route('admin.galeri.foto.destroy', $foto1->id));
        $response->assertSee(route('admin.galeri.foto.destroy', $foto2->id));

        // Verify Lightbox Preview modal
        $response->assertSee('id="historyPhotoModal"', false);
        $response->assertSee('history-photo-modal__close', false);
        $response->assertSee('history-photo-modal__dialog', false);

        // Verify Vite script
        $response->assertSee('history-photo-masonry', false);
    }

    public function test_admin_history_kegiatan_with_no_photos_does_not_break(): void
    {
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Rapat Kerja Tanpa Dokumentasi Foto',
            'kategori' => 'fatayat',
            'description' => 'Kegiatan tanpa foto dokumentasi.',
            'event_date' => now()->subDays(1)->toDateString(),
            'start_time' => '10:00:00',
            'location' => 'Kantor MWC',
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertDontSee('history-photo-masonry__item', false);
        $response->assertSee('Rapat Kerja Tanpa Dokumentasi Foto');
    }

    public function test_public_galeri_is_untouched_and_uses_bento_grid(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Dokumentasi Publik Wangon',
            'kategori' => 'ansor',
            'description' => 'Kegiatan publik.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Wangon',
            'status' => 'published',
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        // Public galeri must NOT have history-photo-masonry
        $response->assertDontSee('history-photo-masonry', false);
        $response->assertDontSee('history-photo-masonry__list', false);
    }

    public function test_admin_history_kegiatan_with_many_photos_renders_all_masonry_items(): void
    {
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Pagelaran Seni Santri Nusantara',
            'kategori' => 'muslimat',
            'description' => 'Dokumentasi lengkap pagelaran seni.',
            'event_date' => now()->subDays(5)->toDateString(),
            'start_time' => '13:00:00',
            'location' => 'Gedung Kesenian',
            'status' => 'published',
        ]);

        for ($i = 1; $i <= 12; $i++) {
            FotoKegiatan::create([
                'kegiatan_id' => $kegiatan->id,
                'file_path' => 'kegiatan/galeri/pagelaran_'.$i.'.jpg',
                'tipe' => 'foto',
                'caption' => 'Momen ke-'.$i,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Foto Dokumentasi (12)');
        for ($i = 1; $i <= 12; $i++) {
            $response->assertSee('pagelaran_'.$i.'.jpg');
            $response->assertSee('Momen ke-'.$i);
        }
    }

    public function test_beranda_and_berita_do_not_contain_history_photo_masonry(): void
    {
        // 1. Homepage
        $home = $this->get(route('home'));
        $home->assertStatus(200);
        $home->assertDontSee('history-photo-masonry', false);

        // 2. Berita index
        $berita = $this->get(route('berita.index'));
        $berita->assertStatus(200);
        $berita->assertDontSee('history-photo-masonry', false);
    }

    public function test_admin_history_kegiatan_renders_inspect_image_modal_with_accessibility_attributes(): void
    {
        $admin = $this->createAdmin();

        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Akbar Hari Santri',
            'kategori' => 'ansor',
            'description' => 'Dokumentasi pengajian akbar.',
            'event_date' => now()->subDays(1)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/pengajian_santri.jpg',
            'tipe' => 'foto',
            'caption' => 'Suasana jamaah pengajian akbar',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galeri.show', $kegiatan->id));

        $response->assertStatus(200);

        // Verify Inspect Image modal markup & accessibility
        $response->assertSee('id="historyPhotoModal"', false);
        $response->assertSee('aria-label="Peninjau Foto"', false);
        $response->assertSee('history-photo-modal__backdrop', false);
        $response->assertSee('history-photo-modal__dialog', false);
        $response->assertSee('aria-label="Tutup preview (Escape)"', false);
        $response->assertSee('id="historyPhotoModalImg"', false);
        $response->assertSee('id="historyPhotoModalCaption"', false);
        $response->assertSee('id="historyPhotoModalDownload"', false);

        // Verify Vite script history-photo-masonry is loaded
        $response->assertSee('history-photo-masonry', false);
    }
}
