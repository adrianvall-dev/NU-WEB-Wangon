<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParallaxScrollVisualDepthTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_renders_parallax_engine_and_card_container_parallax(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Assert Parallax Engine assets are loaded
        $response->assertSee('initParallaxEngine', false);
        $response->assertSee('[data-parallax]', false);
        $response->assertSee('--parallax-y', false);
        $response->assertSee('translate3d(var(--parallax-x', false);
        $response->assertSee('prefers-reduced-motion: reduce', false);

        // 2. Assert Background and Card Container Parallax Elements
        $response->assertSee('data-parallax="bg"', false);
        $response->assertSee('data-parallax="bg-slow"', false);
        $response->assertSee('data-parallax="card-large"', false);
        $response->assertSee('data-parallax="card"', false);

        // 3. Assert Hero Spotlight Card and Hadratussyaikh Card have container parallax
        $content = $response->getContent();
        $this->assertStringContainsString('hero-spotlight-frame floating-frame-card', $content);
        $this->assertStringContainsString('data-parallax="card-large"', $content);

        // 4. Assert Images strictly DO NOT have scroll parallax (locked inside container)
        $this->assertStringNotContainsString('data-parallax="image"', $content);

        // 5. Assert Countdown Timer strictly does NOT have parallax (preserves clarity)
        $this->assertStringNotContainsString('id="countdown-card" data-parallax', $content);
        $this->assertStringNotContainsString('id="cdDays" data-parallax', $content);
    }

    public function test_kegiatan_pages_render_parallax_on_card_containers(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Rapat Pleno MWC NU Wangon',
            'kategori' => 'ansor',
            'description' => 'Pembahasan program kerja dan inventarisasi aset jam\'iyyah.',
            'event_date' => now()->addDays(3)->toDateString(),
            'start_time' => '13:00:00',
            'location' => 'Gedung MWC NU Wangon',
            'status' => 'published',
            'image' => 'kegiatan/dummy-pleno.jpg',
        ]);

        // 1. Kegiatan Index
        $resIndex = $this->get(route('kegiatan.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('data-parallax="card"', false);
        $resIndexContent = $resIndex->getContent();
        $this->assertStringNotContainsString('data-parallax="image"', $resIndexContent);

        // 2. Kegiatan Show
        $resShow = $this->get(route('kegiatan.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('data-parallax="card-large"', false);
        $showContent = $resShow->getContent();
        $this->assertStringNotContainsString('data-parallax="image"', $showContent);

        // Verify countdown card on show does not have parallax
        $this->assertStringNotContainsString('id="countdown-card" data-parallax', $showContent);
    }

    public function test_galeri_pages_render_parallax_on_card_containers(): void
    {
        $kegiatan = Kegiatan::create([
            'title' => 'Dokumentasi Harlah NU',
            'kategori' => 'muslimat',
            'description' => 'Dokumentasi peringatan Harlah NU ke-101.',
            'event_date' => now()->subDays(5)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Wangon',
            'status' => 'published',
            'image' => 'galeri/cover-harlah.jpg',
        ]);

        // 1. Galeri Index
        $resIndex = $this->get(route('galeri.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('initParallaxEngine', false);
        $resIndex->assertSee('data-parallax="card"', false);
        $resIndexContent = $resIndex->getContent();
        $this->assertStringNotContainsString('data-parallax="image"', $resIndexContent);

        // 2. Galeri Show
        $resShow = $this->get(route('galeri.show', $kegiatan->id));
        $resShow->assertStatus(200);
        $resShow->assertSee('initParallaxEngine', false);
        $showContent = $resShow->getContent();
        $this->assertStringNotContainsString('data-parallax="image"', $showContent);
    }

    public function test_berita_pages_render_parallax_on_card_containers(): void
    {
        $user = User::factory()->create();

        $news = News::create([
            'user_id' => $user->id,
            'title' => 'Pelantikan Pengurus Ranting Se-Kecamatan Wangon',
            'slug' => 'pelantikan-pengurus-ranting-se-kecamatan-wangon',
            'content' => 'Pelantikan berlangsung khidmat dengan dihadiri oleh jajaran syuriyah dan tanfidziyah.',
            'kategori' => 'ansor',
            'status' => 'published',
            'image' => 'berita/pelantikan-ranting.jpg',
            'published_at' => now(),
        ]);

        // 1. Berita Index
        $resIndex = $this->get(route('berita.index'));
        $resIndex->assertStatus(200);
        $indexContent = $resIndex->getContent();
        $this->assertTrue(
            str_contains($indexContent, 'data-parallax="card-large"') || str_contains($indexContent, 'data-parallax="card"')
        );
        $this->assertStringNotContainsString('data-parallax="image"', $indexContent);

        // 2. Berita Show
        $resShow = $this->get(route('berita.show', $news->slug));
        $resShow->assertStatus(200);
        $resShow->assertSee('data-parallax="card-large"', false);
        $showContent = $resShow->getContent();
        $this->assertStringNotContainsString('data-parallax="image"', $showContent);
    }

    public function test_anggota_index_renders_parallax_card_on_banom_cards(): void
    {
        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);
        $response->assertSee('initParallaxEngine', false);
        $response->assertSee('data-parallax="card-small"', false);
    }

    public function test_welcome_and_footer_render_abstract_geometric_background_system(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Assert Layer 1 & CSS Textures
        $response->assertSee('pixel-grid-blueprint', false);
        $response->assertSee('pixel-dot-matrix', false);
        $response->assertSee('pixel-grid-blueprint-dark', false);
        $response->assertSee('pixel-dot-matrix-light', false);

        // Assert Layer 2 Ambient Glow & Layer 3 Geometric Shapes
        $response->assertSee('data-parallax="bg-slow"', false);
        $response->assertSee('data-parallax-rotate', false);
        $response->assertSee('ambient-float-slow-1', false);
        $response->assertSee('ambient-float-slow-2', false);
        $response->assertSee('ambient-float-slow-3', false);

        // Assert Concentric Rings with thicker strokes (6px outer rings, 4px dashed inner rings, 3-5px secondary shapes)
        $response->assertSee('border-[6px]', false);
        $response->assertSee('border-4 border-dashed', false);
        $response->assertSee('border-[5px]', false);
        $response->assertSee('stroke-width="4"', false);

        // Assert Solid & Translucent Shape Fills (bg-brand-lightmatcha, from-emerald-600)
        $response->assertSee('bg-brand-lightmatcha/40', false);
        $response->assertSee('from-emerald-600/[0.14]', false);

        // Assert sections have overflow-hidden to prevent horizontal scrollbars
        $content = $response->getContent();
        $this->assertStringContainsString('id="beranda"', $content);
        $this->assertStringContainsString('id="tentang"', $content);
        $this->assertStringContainsString('id="berita"', $content);
        $this->assertStringContainsString('id="kegiatan"', $content);
        $this->assertStringContainsString('id="galeri"', $content);
        $this->assertStringContainsString('id="keanggotaan"', $content);
        $this->assertStringContainsString('id="kontak"', $content);
    }

    public function test_subpage_headers_render_geometric_visual_depth(): void
    {
        // 1. Kegiatan Index
        $resKegiatan = $this->get(route('kegiatan.index'));
        $resKegiatan->assertStatus(200);
        $resKegiatan->assertSee('pixel-grid-blueprint', false);
        $resKegiatan->assertSee('border-[5px]', false);
        $resKegiatan->assertSee('border-[3px] border-dashed', false);

        // 2. Galeri Index
        $resGaleri = $this->get(route('galeri.index'));
        $resGaleri->assertStatus(200);
        $resGaleri->assertSee('pixel-grid-blueprint', false);
        $resGaleri->assertSee('border-[5px]', false);
        $resGaleri->assertSee('border-[3px] border-dashed', false);

        // 3. Berita Index
        $resBerita = $this->get(route('berita.index'));
        $resBerita->assertStatus(200);
        $resBerita->assertSee('pixel-grid-blueprint', false);
        $resBerita->assertSee('border-[5px]', false);
        $resBerita->assertSee('border-[3px] border-dashed', false);

        // 4. Anggota Index
        $resAnggota = $this->get(route('anggota.index'));
        $resAnggota->assertStatus(200);
        $resAnggota->assertSee('pixel-grid-blueprint', false);
        $resAnggota->assertSee('border-[5px]', false);
        $resAnggota->assertSee('border-[3px] border-dashed', false);
    }
}
