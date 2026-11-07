<?php

namespace Tests\Feature;

use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriBentoGridRevampTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_galeri_index_renders_editorial_bento_grid_with_interactions(): void
    {
        // 1. Create a documented activity with photo and video
        $kegiatan1 = Kegiatan::create([
            'title' => 'Konferensi MWC NU Wangon Terbuka',
            'kategori' => 'ansor',
            'description' => 'Dokumentasi konferensi kerja pengurus wakil cabang NU.',
            'event_date' => now()->subDays(4)->toDateString(),
            'start_time' => '08:30:00',
            'location' => 'Gedung MWC NU Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan1->id,
            'file_path' => 'kegiatan/galeri/konferensi.jpg',
            'tipe' => 'foto',
            'caption' => 'Suasana sidang pleno',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan1->id,
            'file_path' => 'kegiatan/galeri/konferensi_clip.mp4',
            'tipe' => 'video',
            'caption' => 'Video cuplikan pembukaan',
        ]);

        // 2. Create second activity
        $kegiatan2 = Kegiatan::create([
            'title' => 'Bakti Sosial Santri Peduli Lingkungan',
            'kategori' => 'fatayat',
            'description' => 'Aksi penanaman pohon dan bersih desa santri.',
            'event_date' => now()->subDays(6)->toDateString(),
            'start_time' => '07:00:00',
            'location' => 'Bantaran Sungai Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan2->id,
            'file_path' => 'kegiatan/galeri/baksos.jpg',
            'tipe' => 'foto',
            'caption' => 'Penanaman bibit pohon',
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);

        // 1. Bento Grid Gallery container & columns
        $response->assertSee('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12', false);
        $response->assertSee('col-span-1 md:col-span-1 lg:col-span-7', false);
        $response->assertSee('col-span-1 md:col-span-1 lg:col-span-5', false);
        $response->assertDontSee('gallery-masonry', false);

        // 2. Card styling & image treatment
        $response->assertSee('data-parallax="card', false);
        $response->assertSee('loading="lazy"', false);

        // 3. Video indicator for item with video
        $response->assertSee('1 Video', false);
        $response->assertSee('bi-play-fill', false);

        // 4. Accessible CTA button with arrow transition
        $response->assertSee('Buka Galeri', false);

        // 5. Dynamic database records
        $response->assertSee('Konferensi MWC NU Wangon Terbuka');
        $response->assertSee('Bakti Sosial Santri Peduli Lingkungan');
        $response->assertSee(route('galeri.show', $kegiatan1->id));
        $response->assertSee(route('galeri.show', $kegiatan2->id));
    }

    public function test_homepage_galeri_section_renders_bento_grid_cards(): void
    {
        // Create 3 documented activities for homepage
        for ($i = 1; $i <= 3; $i++) {
            $keg = Kegiatan::create([
                'title' => 'Dokumentasi Acara Ke-'.$i,
                'kategori' => $i === 1 ? 'muslimat' : ($i === 2 ? 'ansor' : 'fatayat'),
                'description' => 'Rangkaian khidmah dan bakti sosial nomor '.$i,
                'event_date' => now()->subDays($i + 1)->toDateString(),
                'start_time' => '09:00:00',
                'location' => 'Wangon Titik '.$i,
                'status' => 'published',
            ]);

            FotoKegiatan::create([
                'kegiatan_id' => $keg->id,
                'file_path' => 'kegiatan/galeri/foto_'.$i.'.jpg',
                'tipe' => 'foto',
                'caption' => 'Dokumentasi '.$i,
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Bento Grid container on homepage
        $response->assertSee('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6', false);

        // 2. Bento Hero and Companion columns (7 and 5)
        $response->assertSee('col-span-1 md:col-span-1 lg:col-span-7', false);
        $response->assertSee('col-span-1 md:col-span-1 lg:col-span-5', false);

        // 3. Bento Panoramic Banner Card (span 12)
        $response->assertSee('col-span-1 md:col-span-2 lg:col-span-12', false);

        // 4. Accessible touch target on CTA button
        $response->assertSee('Buka Dokumentasi', false);
        $response->assertSee('min-h-[44px] lg:min-h-0', false);
    }

    public function test_bento_grid_strictly_respects_media_filters(): void
    {
        // Kegiatan Foto Only
        $kegFoto = Kegiatan::create([
            'title' => 'Peringatan Hari Santri Nasional',
            'kategori' => 'muslimat',
            'description' => 'Upacara dan karnaval santri.',
            'event_date' => now()->subDays(10)->toDateString(),
            'start_time' => '07:30:00',
            'location' => 'Klapagading Wangon',
            'status' => 'published',
        ]);
        FotoKegiatan::create([
            'kegiatan_id' => $kegFoto->id,
            'file_path' => 'kegiatan/galeri/santri.jpg',
            'tipe' => 'foto',
            'caption' => 'Karnaval santri',
        ]);

        // Kegiatan Video Only
        $kegVideo = Kegiatan::create([
            'title' => 'Pengajian Akbar Sholawat Bersama',
            'kategori' => 'fatayat',
            'description' => 'Malam pembacaan sholawat nariyah.',
            'event_date' => now()->subDays(8)->toDateString(),
            'start_time' => '20:00:00',
            'location' => 'Alun-alun Wangon',
            'status' => 'published',
        ]);
        FotoKegiatan::create([
            'kegiatan_id' => $kegVideo->id,
            'file_path' => 'kegiatan/galeri/sholawat.mp4',
            'tipe' => 'video',
            'caption' => 'Video Sholawat',
        ]);

        // Filter: Video Only
        $resVideo = $this->get(route('galeri.index', ['media' => 'video']));
        $resVideo->assertStatus(200);
        $resVideo->assertSee('Pengajian Akbar Sholawat Bersama');
        $resVideo->assertDontSee('Peringatan Hari Santri Nasional');

        // Filter: Foto Only
        $resFoto = $this->get(route('galeri.index', ['media' => 'foto']));
        $resFoto->assertStatus(200);
        $resFoto->assertSee('Peringatan Hari Santri Nasional');
        $resFoto->assertDontSee('Pengajian Akbar Sholawat Bersama');

        // Filter: Semua
        $resSemua = $this->get(route('galeri.index', ['media' => 'semua']));
        $resSemua->assertStatus(200);
        $resSemua->assertSee('Peringatan Hari Santri Nasional');
        $resSemua->assertSee('Pengajian Akbar Sholawat Bersama');
    }
}
