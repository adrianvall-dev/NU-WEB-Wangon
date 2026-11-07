<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaCardResponsiveProportionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_berita_section_renders_proportional_and_prominent_cards(): void
    {
        $user = User::factory()->create();

        $news = News::create([
            'user_id' => $user->id,
            'title' => 'Kasus Keracunan Massal MBG Meluas, Kepala SPPG Dicopot dan Dapur Operasional Dihentikan',
            'slug' => 'kasus-keracunan-massal-mbg-meluas',
            'content' => 'Pemerintah daerah bersama dinas kesehatan segera melakukan investigasi menyeluruh dan penghentian operasional sementara dapur MBG demi keselamatan warga.',
            'kategori' => 'ansor',
            'status' => 'published',
            'image' => 'berita/mbg-kasus.jpg',
            'published_at' => now(),
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Assert Large News Image container (100% width, aspect-16/9 mobile, 16/10 desktop, border-b-2)
        $response->assertSee('aspect-[16/9] sm:aspect-[16/10] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-stone-100 border-b-2 border-brand-forest', false);
        $response->assertSee('data-parallax="card-large"', false);

        // 2. Assert Content Wrapper and strict left alignment
        $response->assertSee('px-5 sm:px-6 pt-5 sm:pt-6 pb-6 sm:pb-7 flex flex-col flex-1 text-left w-full', false);

        // 3. Assert Category Badge (11-13px font-size, 12-16px horizontal, 6-8px vertical padding)
        $response->assertSee('px-3.5 py-1.5 sm:px-4 sm:py-1.5 rounded-lg bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest text-xs sm:text-[13px] font-black uppercase tracking-wider', false);

        // 4. Assert Readable Date Metadata (13-14px font-size)
        $response->assertSee('text-[13px] sm:text-sm font-bold text-brand-darktext/70', false);

        // 5. Assert Prominent Title (20-24px on mobile, font-weight 600-700 / black, line-height 1.15-1.3, line-clamp-2, text-left)
        $response->assertSee('text-left text-[20px] sm:text-[22px]', false);
        $response->assertSee('font-black text-brand-deep leading-[1.22]', false);

        // 6. Assert Readable Excerpt (14-16px, line-height 1.5-1.7, line-clamp-2 to 3, text-left)
        $response->assertSee('text-left text-sm sm:text-[15px] font-medium text-brand-darktext/80 leading-[1.55] sm:leading-[1.6] line-clamp-2 sm:line-clamp-3 mt-2 sm:mt-2.5 mb-0', false);

        // 7. Assert Footer Action with Two-Line Left Label and Right-Aligned BACA button
        $response->assertSee('WARTA NU', false);
        $response->assertSee('WANGON', false);
        $response->assertSee('h-11 px-6 sm:px-7 rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-xs sm:text-sm uppercase tracking-wider', false);
    }

    public function test_berita_index_renders_proportional_and_prominent_cards(): void
    {
        $user = User::factory()->create();

        // Create 2 articles to populate both featured and regular grid
        $news1 = News::create([
            'user_id' => $user->id,
            'title' => 'Warta Berita Utama Pertama NU Wangon',
            'slug' => 'warta-berita-utama-pertama-nu-wangon',
            'content' => 'Isi berita utama yang sangat informatif dan lengkap untuk pembaca.',
            'kategori' => 'fatayat',
            'status' => 'published',
            'image' => 'berita/utama.jpg',
            'published_at' => now(),
        ]);

        $news2 = News::create([
            'user_id' => $user->id,
            'title' => 'Kasus Keracunan Massal MBG Meluas, Kepala SPPG Dicopot dan Dapur Operasional Dihentikan',
            'slug' => 'kasus-keracunan-massal-mbg-meluas',
            'content' => 'Pemerintah daerah bersama dinas kesehatan segera melakukan investigasi menyeluruh dan penghentian operasional sementara.',
            'kategori' => 'ansor',
            'status' => 'published',
            'image' => 'berita/mbg-kasus.jpg',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);

        // 1. Assert Bento Card Image Container
        $response->assertSee('aspect-[16/9] sm:aspect-[16/10] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-stone-100 border-b-2 border-brand-forest', false);

        // 2. Assert Title & Excerpt Proportions and Left Alignment in Bento Grid
        $response->assertSee('text-left text-[20px] sm:text-[22px]', false);
        $response->assertSee('text-left text-sm sm:text-[15px] font-medium text-brand-darktext/80', false);

        // 3. Assert Proportional CTA Button with BACA text and two-line footer label
        $response->assertSee('WARTA NU', false);
        $response->assertSee('WANGON', false);
        $response->assertSee('h-11 px-6 sm:px-7 rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-xs sm:text-sm', false);
    }
}
