<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaAsymmetricBentoGridTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_renders_responsive_editorial_grid_with_fallback_articles(): void
    {
        // Without database news, fallback articles render in the responsive editorial grid
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Assert 2-col mobile & 12-col desktop responsive grid container with generous breathing room
        $response->assertSee('grid grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-7 sm:gap-x-6 sm:gap-y-8 lg:gap-x-7 lg:gap-y-10 responsive-news-grid', false);

        // 2. Assert that old cramped grids are NOT present
        $response->assertDontSee('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8', false);
        $response->assertDontSee('grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4', false);

        // 3. Assert presence of editorial variants
        $response->assertSee('data-bento-variant="featured"', false);
        $response->assertSee('data-bento-variant="horizontal"', false);
        $response->assertSee('data-bento-variant="editorial"', false);

        // 4. Assert mobile 2-col asymmetric rhythm (col-span-2 / col-span-1) with locked desktop 12-col spans
        $response->assertSee('col-span-2 lg:col-span-7', false);
        $response->assertSee('col-span-1 lg:col-span-5', false);
        $response->assertSee('col-span-1 lg:col-span-12', false);
        $response->assertSee('col-span-2 lg:col-span-4', false);
        $response->assertSee('col-span-1 lg:col-span-4', false);

        // 5. Assert inner image radius clamping to prevent corner bleeding
        $response->assertSee('rounded-t-[14px] sm:rounded-t-[22px]', false);
    }

    public function test_welcome_page_renders_dynamic_database_news_in_editorial_grid(): void
    {
        $user = User::factory()->create();

        // Create 6 published news items with distinct categories
        $categories = ['ansor', 'fatayat', 'muslimat', 'ansor', 'fatayat', 'muslimat'];
        for ($i = 1; $i <= 6; $i++) {
            News::create([
                'user_id' => $user->id,
                'title' => "Warta Berita Dinamis Ke-$i MWC NU Wangon",
                'slug' => "warta-berita-dinamis-ke-$i-mwc-nu-wangon",
                'content' => "Isi lengkap warta berita dinamis nomor $i seputar khidmah warga nahdliyin.",
                'kategori' => $categories[$i - 1],
                'status' => 'published',
                'image' => "berita/foto-$i.jpg",
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify dynamic news titles rendered
        $response->assertSee('Warta Berita Dinamis Ke-1 MWC NU Wangon');
        $response->assertSee('Warta Berita Dinamis Ke-2 MWC NU Wangon');
        $response->assertSee('Warta Berita Dinamis Ke-3 MWC NU Wangon');
        $response->assertSee('Warta Berita Dinamis Ke-4 MWC NU Wangon');
        $response->assertSee('Warta Berita Dinamis Ke-5 MWC NU Wangon');
        $response->assertSee('Warta Berita Dinamis Ke-6 MWC NU Wangon');

        // Verify Editorial attributes
        $response->assertSee('data-bento-variant="featured"', false);
        $response->assertSee('data-bento-variant="horizontal"', false);
        $response->assertSee('data-bento-variant="editorial"', false);

        // Verify Parallax and Micro-interactions
        $response->assertSee('data-parallax="card-large"', false);
        $response->assertSee('data-parallax="card"', false);
    }

    public function test_berita_index_renders_responsive_editorial_grid(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 6; $i++) {
            News::create([
                'user_id' => $user->id,
                'title' => "Artikel Arsip Berita Ke-$i MWC NU Wangon",
                'slug' => "artikel-arsip-berita-ke-$i-mwc-nu-wangon",
                'content' => "Konten arsip berita nomor $i untuk direktori warta berita.",
                'kategori' => ($i % 2 === 0) ? 'ansor' : 'fatayat',
                'status' => 'published',
                'image' => "berita/arsip-$i.jpg",
                'published_at' => now()->subHours($i * 2),
            ]);
        }

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);

        // Assert 2-col mobile & 12-col desktop grid container with generous breathing room
        $response->assertSee('grid grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-7 sm:gap-x-6 sm:gap-y-8 lg:gap-x-7 lg:gap-y-10 responsive-news-grid', false);

        // Assert Editorial Variants
        $response->assertSee('data-bento-variant="featured"', false);
        $response->assertSee('data-bento-variant="horizontal"', false);
        $response->assertSee('data-bento-variant="editorial"', false);
    }

    public function test_editorial_grid_adapts_robustly_to_single_item(): void
    {
        $user = User::factory()->create();

        News::create([
            'user_id' => $user->id,
            'title' => 'Satu-Satunya Berita Utama Tunggal NU Wangon',
            'slug' => 'satu-satunya-berita-utama-tunggal-nu-wangon',
            'content' => 'Ketika hanya ada 1 berita, card featured harus span 12 kolom desktop.',
            'kategori' => 'fatayat',
            'status' => 'published',
            'image' => 'berita/tunggal.jpg',
            'published_at' => now(),
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // With 1 item, Featured spans full 12 columns on desktop
        $response->assertSee('col-span-2 lg:col-span-12', false);
        $response->assertSee('data-bento-variant="featured"', false);
    }

    public function test_editorial_grid_adapts_robustly_to_three_items(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 3; $i++) {
            News::create([
                'user_id' => $user->id,
                'title' => "Trio Berita Ke-$i NU Wangon",
                'slug' => "trio-berita-ke-$i-nu-wangon",
                'content' => "Konten trio berita nomor $i.",
                'kategori' => 'ansor',
                'status' => 'published',
                'image' => "berita/trio-$i.jpg",
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Item 0: Hero 7 cols desktop, full width mobile
        $response->assertSee('col-span-2 lg:col-span-7', false);
        // Item 1: Highlight 5 cols desktop, compact half width mobile
        $response->assertSee('col-span-1 lg:col-span-5', false);
        // Item 2: Horizontal Magazine 12 cols desktop, compact half width mobile
        $response->assertSee('col-span-1 lg:col-span-12', false);
    }
}
