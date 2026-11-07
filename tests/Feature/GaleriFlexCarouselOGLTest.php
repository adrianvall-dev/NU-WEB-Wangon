<?php

namespace Tests\Feature;

use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriFlexCarouselOGLTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_galeri_detail_history_does_not_render_flex_carousel_and_uses_normal_layout(): void
    {
        // 1. Create a documented activity with photo and video
        $kegiatan = Kegiatan::create([
            'title' => 'Peringatan Harlah NU Wangon Ke-101',
            'kategori' => 'ansor',
            'description' => 'Dokumentasi peringatan harlah nahdlatul ulama dengan istighosah akbar.',
            'event_date' => now()->subDays(3)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Alun-alun Wangon',
            'status' => 'published',
        ]);

        $foto = FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/harlah_sholawat.jpg',
            'tipe' => 'foto',
            'caption' => 'Lautan jamaah bersholawat bersama',
        ]);

        $video = FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/video/harlah_cuplikan.mp4',
            'tipe' => 'video',
            'caption' => 'Video cuplikan pembukaan acara',
        ]);

        // 2. Request the public detail history kegiatan
        $response = $this->get(route('galeri.show', $kegiatan->id));

        $response->assertStatus(200);

        // 3. Strictly verify FlexCarousel components are REMOVED
        $response->assertDontSee('history-detail-carousel', false);
        $response->assertDontSee('flex-carousel', false);
        $response->assertDontSee('history-detail-carousel__stage', false);
        $response->assertDontSee('flex-carousel__stage', false);
        $response->assertDontSee('FLEXCAROUSEL', false);
        $response->assertDontSee('Liquid 3D Distortion & Natural Ratio', false);
        $response->assertDontSee('data-carousel-prev', false);
        $response->assertDontSee('data-carousel-next', false);
        $response->assertDontSee('data-carousel-counter', false);
        $response->assertDontSee('data-carousel-action-btn', false);
        $response->assertDontSee('id="history-carousel-data"', false);
        $response->assertDontSee('history-detail-carousel.js', false);

        // 4. Verify normal documentation sections ARE present
        $response->assertSee('id="videoContainer"', false);
        $response->assertSee('id="photoContainer"', false);
        $response->assertSee('Video Dokumentasi Acara', false);
        $response->assertSee('Foto Dokumentasi Acara', false);
        $response->assertSee('id="photoLightbox"', false);

        // 5. Verify database photos & videos are displayed
        $response->assertSee('Peringatan Harlah NU Wangon Ke-101', false);
        $response->assertSee('kegiatan/galeri/harlah_sholawat.jpg', false);
        $response->assertSee('kegiatan/video/harlah_cuplikan.mp4', false);
        $response->assertSee('Lautan jamaah bersholawat bersama', false);
        $response->assertSee('Video cuplikan pembukaan acara', false);
    }

    public function test_galeri_index_and_homepage_do_not_render_history_detail_flex_carousel(): void
    {
        // 1. Check homepage
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('history-detail-carousel', false);
        $homeResponse->assertDontSee('FLEXCAROUSEL', false);

        // 2. Check public galeri index / listing
        $galeriResponse = $this->get(route('galeri.index'));
        $galeriResponse->assertStatus(200);
        $galeriResponse->assertDontSee('history-detail-carousel', false);
        $galeriResponse->assertDontSee('FLEXCAROUSEL', false);
    }

    public function test_media_filter_tabs_toggle_normal_video_and_photo_containers(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Rutin Ahad Pagi',
            'kategori' => 'muslimat',
            'description' => 'Kajian kitab kuning rutin ahad pagi.',
            'event_date' => now()->subDays(5)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Masjid Besar Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/pengajian.jpg',
            'tipe' => 'foto',
            'caption' => 'Jamaah menyimak mauidhoh hasanah',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/video/pengajian.mp4',
            'tipe' => 'video',
            'caption' => 'Video ceramah ustadzah',
        ]);

        $response = $this->get(route('galeri.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee("switchMediaTab('semua')", false);
        $response->assertSee("switchMediaTab('foto')", false);
        $response->assertSee("switchMediaTab('video')", false);
        $response->assertDontSee('historyDetailCarouselInstance', false);
    }

    public function test_lightbox_has_accessible_isolated_navigation_buttons_and_dedicated_backdrop(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Santunan Anak Yatim',
            'kategori' => 'fatayat',
            'description' => 'Kegiatan santunan dan doa bersama anak yatim piatu.',
            'event_date' => now()->subDays(2)->toDateString(),
            'start_time' => '09:00:00',
            'location' => 'Gedung MWC NU Wangon',
            'status' => 'published',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/santunan_1.jpg',
            'tipe' => 'foto',
            'caption' => 'Penyerahan bingkisan dan santunan',
        ]);

        FotoKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'file_path' => 'kegiatan/galeri/santunan_2.jpg',
            'tipe' => 'foto',
            'caption' => 'Doa bersama kyai dan pengurus',
        ]);

        $response = $this->get(route('galeri.show', $kegiatan->id));

        $response->assertStatus(200);

        // Verify dedicated backdrop element exists and is separated from container
        $response->assertSee('id="photoLightboxBackdrop"', false);

        // Verify dedicated high-contrast navigation and close buttons exist
        $response->assertSee('id="lightboxCloseBtn"', false);
        $response->assertSee('id="lightboxPrevBtn"', false);
        $response->assertSee('id="lightboxNextBtn"', false);

        // Verify content container and image exist
        $response->assertSee('id="lightboxContent"', false);
        $response->assertSee('id="lightboxImage"', false);
        $response->assertSee('id="lightboxCaption"', false);
        $response->assertSee('id="lightboxCounter"', false);

        // Verify navigation logic & event stop propagation scripts exist
        $response->assertSee('e.stopPropagation()', false);
        $response->assertSee('nextPhoto', false);
        $response->assertSee('prevPhoto', false);
        $response->assertSee('closeLightbox', false);
    }
}
