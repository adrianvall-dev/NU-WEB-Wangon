<?php

namespace Tests\Feature;

use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaRelatedSectionsRevampTest extends TestCase
{
    use RefreshDatabase;

    public function test_berita_show_renders_warta_lainnya_with_max_3_column_desktop_and_large_16_by_9_cards(): void
    {
        $user = User::factory()->create();

        $mainNews = News::create([
            'user_id' => $user->id,
            'title' => 'Berita Utama Yang Sedang Dibaca Pengunjung',
            'slug' => 'berita-utama-yang-sedang-dibaca-pengunjung',
            'content' => 'Konten lengkap berita utama...',
            'kategori' => 'ansor',
            'status' => 'published',
            'image' => 'berita/utama.jpg',
            'published_at' => now(),
        ]);

        for ($i = 1; $i <= 4; $i++) {
            News::create([
                'user_id' => $user->id,
                'title' => "Warta Terkait Nomor $i Seputar MWCNU",
                'slug' => "warta-terkait-nomor-$i-seputar-mwcnu",
                'content' => "Ringkasan konten warta terkait nomor $i...",
                'kategori' => ($i % 2 === 0) ? 'fatayat' : 'muslimat',
                'status' => 'published',
                'image' => "berita/terkait-$i.jpg",
                'published_at' => now()->subHours($i * 3),
            ]);
        }

        $response = $this->get(route('berita.show', $mainNews->slug));

        $response->assertStatus(200);

        // 1. Assert 3-column desktop grid for related content (no more cramped 4-column) with generous breathing room
        $response->assertSee('grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-7 sm:gap-x-6 sm:gap-y-8 lg:gap-x-7 lg:gap-y-10 related-content-grid', false);
        $response->assertDontSee('grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6', false);

        // 2. Assert large 16:9 widescreen aspect ratio (no more short h-36 thumbnail)
        $response->assertSee('aspect-[16/9]', false);
        $response->assertDontSee('relative h-36 rounded-2xl overflow-hidden', false);

        // 3. Assert corner radius clamping
        $response->assertSee('rounded-t-[14px] sm:rounded-t-[22px]', false);

        // 4. Assert related news titles are rendered
        $response->assertSee('Warta Terkait Nomor 1 Seputar MWCNU');
        $response->assertSee('Warta Terkait Nomor 2 Seputar MWCNU');
        $response->assertSee('Warta Terkait Nomor 3 Seputar MWCNU');
        $response->assertSee('Warta Terkait Nomor 4 Seputar MWCNU');

        // 5. Assert mobile asymmetric spans: full width anchors & half width pair
        $response->assertSee('col-span-2 lg:col-span-1', false);
        $response->assertSee('col-span-1 lg:col-span-1', false);
    }

    public function test_berita_show_renders_galeri_lainnya_section_with_large_photos(): void
    {
        $user = User::factory()->create();

        $mainNews = News::create([
            'user_id' => $user->id,
            'title' => 'Berita Dengan Galeri Terkait',
            'slug' => 'berita-dengan-galeri-terkait',
            'content' => 'Konten berita...',
            'kategori' => 'ansor',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $kegiatan = Kegiatan::create([
            'user_id' => $user->id,
            'title' => 'Dokumentasi Apel Akbar Banser Wangon',
            'description' => 'Ratusan kader apel akbar.',
            'event_date' => now()->subDays(2),
            'start_time' => '08:00',
            'location' => 'Alun-alun Wangon',
            'status' => 'published',
            'kategori' => 'ansor',
            'image' => 'kegiatan/apel.jpg',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/foto-1.jpg',
            'tipe' => 'foto',
            'caption' => 'Barisan Banser',
        ]);

        $response = $this->get(route('berita.show', $mainNews->slug));

        $response->assertStatus(200);

        // Assert Galeri Lainnya section rendered
        $response->assertSee('Galeri Lainnya');
        $response->assertSee('Dokumentasi Apel Akbar Banser Wangon');
        $response->assertSee('Foto', false);
        $response->assertSee('Lihat Foto', false);
    }

    public function test_galeri_show_renders_related_galeri_with_large_16_by_9_focal_point_photos(): void
    {
        $user = User::factory()->create();

        $kegiatan1 = Kegiatan::create([
            'user_id' => $user->id,
            'title' => 'Galeri Utama Pelantikan Pengurus',
            'description' => 'Pelantikan resmi...',
            'event_date' => now()->subDays(5),
            'start_time' => '09:00',
            'location' => 'Gedung MWC',
            'status' => 'published',
            'kategori' => 'ansor',
            'image' => 'kegiatan/keg-1.jpg',
        ]);

        $kegiatan2 = Kegiatan::create([
            'user_id' => $user->id,
            'title' => 'Dokumentasi Pengajian Akbar MWCNU',
            'description' => 'Pengajian akbar...',
            'event_date' => now()->subDays(10),
            'start_time' => '13:00',
            'location' => 'Masjid Besar Wangon',
            'status' => 'published',
            'kategori' => 'muslimat',
            'image' => 'kegiatan/keg-2.jpg',
        ]);

        $response = $this->get(route('galeri.show', $kegiatan1->id));

        $response->assertStatus(200);

        // Assert Galeri Kegiatan Lainnya uses large 16:9 aspect ratio and 3-column grid
        $response->assertSee('Galeri Kegiatan Lainnya');
        $response->assertSee('grid grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6 lg:gap-7 related-content-grid', false);
        $response->assertSee('aspect-[16/9]', false);
        $response->assertDontSee('relative h-44 rounded-2xl overflow-hidden', false);
        $response->assertSee('Dokumentasi Pengajian Akbar MWCNU');
    }

    public function test_desktop_main_news_grids_remain_strictly_locked(): void
    {
        $responseHome = $this->get(route('home'));
        $responseHome->assertStatus(200);
        $responseHome->assertSee('lg:grid-cols-12', false);
        $responseHome->assertSee('lg:col-span-7', false);
        $responseHome->assertSee('lg:col-span-5', false);
        $responseHome->assertSee('lg:col-span-12', false);

        $responseIndex = $this->get(route('berita.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('lg:grid-cols-12', false);
    }
}
