<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BerandaFixedNavbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_renders_with_fixed_top_navbar_and_without_top_announcement_bar(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Verify top announcement bar (Resmi, Agenda Terdekat: ..., Lihat Rincian) above navbar is removed
        $content = $response->getContent();
        $this->assertStringNotContainsString('Lihat Rincian &rarr;', $content);
        $this->assertStringNotContainsString('Lihat Rincian →', $content);
        $this->assertStringNotContainsString('#kegiatan" class="text-xs text-brand-matcha font-bold', $content);

        // 2. Verify fixed top navbar classes
        $response->assertSee('class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#F6F4EE] border-b-2 border-brand-forest"', false);

        // 3. Verify CSS offset configuration for fixed navbar
        $response->assertSee('--nav-height: 80px;', false);
        $response->assertSee('scroll-padding-top: 96px;', false);
        $response->assertSee('padding-top: var(--nav-height);', false);

        // 4. Verify all primary navigation links exist
        $response->assertSee(route('home').'#beranda');
        $response->assertSee(route('home').'#tentang');
        $response->assertSee(route('kegiatan.index'));
        $response->assertSee(route('berita.index'));
        $response->assertSee(route('galeri.index'));
        $response->assertSee(route('anggota.index'));
        $response->assertSee(route('anggota.daftar'));
    }

    public function test_kegiatan_page_renders_with_fixed_navbar_and_without_broken_layout(): void
    {
        $response = $this->get(route('kegiatan.index'));

        $response->assertStatus(200);
        $response->assertSee('fixed top-0 left-0 right-0 w-full z-[9999]', false);
        $response->assertSee('padding-top: var(--nav-height);', false);
    }

    public function test_berita_page_renders_with_fixed_navbar(): void
    {
        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertSee('fixed top-0 left-0 right-0 w-full z-[9999]', false);
    }

    public function test_galeri_page_renders_with_fixed_navbar(): void
    {
        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertSee('fixed top-0 left-0 right-0 w-full z-[9999]', false);
    }

    public function test_navbar_motion_trail_tabs_elements_and_routes_render_correctly(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Verify Motion Trail container and elements
        $response->assertSee('id="desktopNav"', false);
        $response->assertSee('motion-trail-nav', false);
        $response->assertSee('id="motionTrailIndicator"', false);
        $response->assertSee('id="motionTrailSvg"', false);
        $response->assertSee('motion-trail-path-1', false);
        $response->assertSee('motion-trail-path-2', false);

        // 2. Verify all nav links have nav-trail-link class and data-nav-id
        $response->assertSee('data-nav-id="beranda"', false);
        $response->assertSee('data-nav-id="tentang"', false);
        $response->assertSee('data-nav-id="agenda"', false);
        $response->assertSee('data-nav-id="berita"', false);
        $response->assertSee('data-nav-id="galeri"', false);
        $response->assertSee('data-nav-id="anggota"', false);

        // 3. Verify mobile menu elements remain intact
        $response->assertSee('x-show="mobileMenuOpen"', false);
    }

    public function test_navbar_active_state_matches_current_route(): void
    {
        // 1. Home page: Beranda is active
        $resHome = $this->get(route('home'));
        $resHome->assertStatus(200);
        $this->assertMatchesRegularExpression('/data-nav-id="beranda"[^>]*is-active/', $resHome->getContent());

        // 2. Agenda page: Agenda is active
        $resKegiatan = $this->get(route('kegiatan.index'));
        $resKegiatan->assertStatus(200);
        $this->assertMatchesRegularExpression('/data-nav-id="agenda"[^>]*is-active/', $resKegiatan->getContent());

        // 3. Berita page: Berita is active
        $resBerita = $this->get(route('berita.index'));
        $resBerita->assertStatus(200);
        $this->assertMatchesRegularExpression('/data-nav-id="berita"[^>]*is-active/', $resBerita->getContent());

        // 4. Galeri page: Galeri is active
        $resGaleri = $this->get(route('galeri.index'));
        $resGaleri->assertStatus(200);
        $this->assertMatchesRegularExpression('/data-nav-id="galeri"[^>]*is-active/', $resGaleri->getContent());

        // 5. Anggota page: Anggota is active
        $resAnggota = $this->get(route('anggota.index'));
        $resAnggota->assertStatus(200);
        $this->assertMatchesRegularExpression('/data-nav-id="anggota"[^>]*is-active/', $resAnggota->getContent());
    }
}
