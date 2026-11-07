<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileAndroidNavbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_navbar_composition_renders_compact_header_and_hamburger(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Header height responsive: 64px mobile (h-16), 80px desktop (lg:h-20)
        $response->assertSee('h-16 lg:h-20 flex items-center justify-between', false);

        // Header full width viewport containment and safe area top padding
        $response->assertSee('style="padding-top: env(safe-area-inset-top, 0px); width: 100%; max-width: 100%;"', false);

        // Top bar container is relative z-[10000] to sit above backdrop and drawer
        $response->assertSee('relative z-[10000]', false);

        // Brand logo & title with truncation protection and flex-1 shrinking protection
        $response->assertSee('images/landing/logo-nu.png', false);
        $response->assertSee('ORGANISASI', false);
        $response->assertSee('NU WANGON', false);
        $response->assertSee('Majelis Wakil Cabang', false);
        $response->assertSee('min-w-0 flex-1 lg:flex-initial pr-2', false);

        // Right side container and hamburger touch target have shrink-0 to prevent being pushed off-viewport
        $response->assertSee('flex items-center gap-2 sm:gap-3 shrink-0', false);
        $response->assertSee('w-11 h-11 rounded-xl bg-brand-sand border-2 border-brand-forest', false);
        $response->assertSee('Toggle Menu Navigasi Mobile', false);

        // Smooth 3-line hamburger to 'X' animation elements
        $response->assertSee(":class=\"mobileMenuOpen ? 'rotate-45 translate-y-[7px]' : ''\"", false);
        $response->assertSee(":class=\"mobileMenuOpen ? 'opacity-0 scale-x-0' : 'opacity-100'\"", false);
        $response->assertSee(":class=\"mobileMenuOpen ? '-rotate-45 -translate-y-[7px]' : ''\"", false);
    }

    public function test_mobile_drawer_contains_all_routes_and_accessible_touch_targets(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Backdrop overlay
        $response->assertSee('class="fixed inset-0 bg-brand-deep/75 backdrop-blur-xs z-[9990] lg:hidden touch-manipulation mobile-backdrop"', false);

        // Mobile drawer container with fallback safe-area calculation and zero-horizontal-overflow shadow
        $response->assertSee('shadow-[0_8px_0_0_#142E25]', false);
        $response->assertSee('style="top: 64px; top: calc(var(--nav-height, 64px) + env(safe-area-inset-top, 0px)); max-height: calc(100vh - 64px); max-height: calc(100dvh - var(--nav-height, 64px) - env(safe-area-inset-top, 0px));', false);
        $response->assertSee('overscroll-contain', false);

        // Navigation links
        $response->assertSee(route('home').'#beranda');
        $response->assertSee(route('home').'#tentang');
        $response->assertSee(route('kegiatan.index'));
        $response->assertSee(route('berita.index'));
        $response->assertSee(route('galeri.index'));
        $response->assertSee(route('anggota.index'));

        // All menu item touch targets meet >= 48px requirement
        $response->assertSee('min-h-[48px]', false);
    }

    public function test_mobile_drawer_active_state_highlights_current_page(): void
    {
        // When on Beranda
        $resHome = $this->get(route('home'));
        $resHome->assertStatus(200);
        $resHome->assertSee('bg-brand-matcha text-white text-[9px] font-black uppercase rounded-md tracking-wider">Aktif</span>', false);

        // When on Berita
        $resBerita = $this->get(route('berita.index'));
        $resBerita->assertStatus(200);
        $this->assertStringContainsString('Warta &amp; Berita', $resBerita->getContent());

        // When on Galeri
        $resGaleri = $this->get(route('galeri.index'));
        $resGaleri->assertStatus(200);
        $this->assertStringContainsString('Galeri Dokumentasi', $resGaleri->getContent());
    }

    public function test_badan_otonom_accordion_contains_all_sub_organizations(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Banom Accordion
        $response->assertSee('Badan Otonom (Banom)', false);
        $response->assertSee('x-show="mobileBanomOpen"', false);

        // All Banom links
        $response->assertSee(route('anggota.index', ['organisasi' => 'ansor']));
        $response->assertSee(route('anggota.index', ['organisasi' => 'fatayat']));
        $response->assertSee(route('anggota.index', ['organisasi' => 'muslimat']));
        $response->assertSee(route('anggota.index', ['organisasi' => 'ipnu']));
        $response->assertSee(route('anggota.index', ['organisasi' => 'ippnu']));
        $response->assertSee(route('anggota.index', ['organisasi' => 'pagar_nusa']));
    }

    public function test_mobile_ctas_and_guest_authentication_controls(): void
    {
        // Guest user
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Anggota (KARTANU)', false);
        $response->assertSee(route('anggota.daftar'));
        $response->assertSee('Cek Status KARTANU', false);
        $response->assertSee(route('anggota.cek-status'));
        $response->assertSee('Masuk Akun Admin', false);
        $response->assertSee(route('login'));
    }

    public function test_mobile_auth_state_shows_user_profile_and_logout_button(): void
    {
        $admin = User::factory()->create([
            'name' => 'Ahmad Pengurus',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Pengurus');
        $response->assertSee('admin');
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee(route('logout'));
        $response->assertSee('Keluar dari Akun');
    }

    public function test_desktop_navbar_remains_strictly_intact_and_locked(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Desktop nav element with motion trail indicator
        $response->assertSee('id="desktopNav"', false);
        $response->assertSee('motion-trail-nav hidden lg:flex', false);
        $response->assertSee('id="motionTrailIndicator"', false);
        $response->assertSee('id="motionTrailSvg"', false);

        // Desktop CTA and dropdown only show on lg: screens
        $response->assertSee('hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-brand-deep', false);
        $response->assertSee('hidden lg:block relative', false);

        // Desktop root height 80px config
        $response->assertSee('--nav-height: 80px;', false);
    }

    public function test_viewport_overflow_protection_for_mobile_and_desktop_viewports(): void
    {
        $response = $this->get(route('home'));
        $content = $response->getContent();

        // 1. Verify mobile viewports (320px, 360px, 375px, 390px, 412px, 430px) protection:
        // No fixed width in pixels on navbar or header container
        $this->assertStringNotContainsString('width: 1200px;', $content);
        $this->assertStringNotContainsString('width: 1440px;', $content);

        // Header and drawer have w-full max-w-full
        $this->assertStringContainsString('w-full max-w-full lg:max-w-7xl', $content);
        $this->assertStringContainsString('class="fixed left-0 right-0 w-full max-w-full z-[9995] lg:hidden', $content);

        // Right side container is shrink-0 to prevent hamburger push-off
        $this->assertStringContainsString('flex items-center gap-2 sm:gap-3 shrink-0', $content);

        // Hamburger button itself is shrink-0
        $this->assertStringContainsString('shrink-0 lg:hidden w-11 h-11', $content);

        // Brand container allows text truncation (min-w-0 flex-1)
        $this->assertStringContainsString('min-w-0 flex-1 lg:flex-initial pr-2', $content);

        // 2. Verify desktop viewports (1366px, 1440px, 1920px) non-regression:
        $this->assertStringContainsString('id="desktopNav"', $content);
        $this->assertStringContainsString('hidden lg:flex items-center relative p-1 rounded-2xl bg-brand-sand/65', $content);
        $this->assertStringContainsString('h-16 lg:h-20', $content);
    }
}
