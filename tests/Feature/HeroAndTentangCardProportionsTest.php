<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroAndTentangCardProportionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_spotlight_card_proportions_and_metadata_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Outer card padding: adjusted for mobile/tablet while preserving desktop lg:p-6
        $response->assertSee('p-3.5 sm:p-5 lg:p-6 overflow-hidden relative group hero-spotlight-frame floating-frame-card', false);

        // 2. Image container: wider aspect ratio with object-cover and 100% width
        $response->assertSee('aspect-[15/11] sm:aspect-[14/11] lg:aspect-[14/11] bg-stone-100 group', false);
        $response->assertSee('images/landing/hero-flag.jpg', false);
        $response->assertSee('Dokumentasi Konferensi MWC NU Wangon', false);

        // 3. Floating overlay badge inside image container
        $response->assertSee('floating-badge-drift', false);
        $response->assertSee('Konferensi MWC NU Wangon', false);
        $response->assertSee('Aula Sindurjan, Banyumas', false);

        // 4. Solid spec grid with breathing room
        $response->assertSee('Berdiri', false);
        $response->assertSee('1926 M', false);
        $response->assertSee('Kultur', false);
        $response->assertSee('Aswaja', false);
        $response->assertSee('Wilayah', false);
        $response->assertSee('Wangon', false);
        $response->assertSee('Khidmah', false);
        $response->assertSee('Umat', false);

        // 5. Verification status items
        $response->assertSee('Tradisi Keilmuan Terjaga', false);
        $response->assertSee('Terverifikasi Resmi', false);
    }

    public function test_hadratussyaikh_card_proportions_and_metadata_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Outer card padding: adjusted for mobile/tablet while preserving desktop lg:p-5
        $response->assertSee('p-3.5 sm:p-4.5 lg:p-5 rounded-3xl border-2 border-brand-forest shadow-solid-lg floating-frame-card', false);

        // 2. Image container: aspect 4/3 full width with object-cover
        $response->assertSee('aspect-[4/3] bg-stone-100 w-full', false);
        $response->assertSee('images/landing/hasyim-asyari.jpg', false);
        $response->assertSee("Hadratussyaikh KH. M. Hasyim Asy'ari", false);

        // 3. Information spacing & text hierarchy
        $response->assertSee('Hadratussyaikh', false);
        $response->assertSee("KH. M. Hasyim Asy'ari", false);
        $response->assertSee('Pendiri Nahdlatul Ulama — 1871–1947 M', false);
        $response->assertSee('bi-book-half', false);

        // 4. Spec grid: Deklarasi, Tempat, Khidmah with natural padding
        $response->assertSee('Deklarasi', false);
        $response->assertSee('16 Rajab 1344', false);
        $response->assertSee('Tempat', false);
        $response->assertSee('Surabaya', false);
        $response->assertSee('Khidmah', false);
        $response->assertSee('Aswaja', false);
    }
}
