<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayfulMagneticButtonInteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_renders_magnetic_playful_button_engine_and_ripples(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Verify engine initialization and classes are present
        $response->assertSee('__playfulMagneticEngineLoaded', false);
        $response->assertSee('mag-active', false);
        $response->assertSee('magRippleAnim', false);
        $response->assertSee('mag-click-ripple', false);

        // 2. Verify strict navbar exclusion is embedded in the engine
        $response->assertSee("target.closest('#mainNavbarHeader, header, [data-navbar], .site-header, .motion-trail-nav, #desktopNav, .navbar, #navbarMain')", false);

        // 3. Verify Navbar header element is explicitly marked for immunity
        $response->assertSee('id="mainNavbarHeader"', false);
        $response->assertSee('data-navbar="true"', false);
    }

    public function test_public_pages_include_magnetic_interaction_engine(): void
    {
        $routes = [
            route('kegiatan.index'),
            route('berita.index'),
            route('galeri.index'),
            route('anggota.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee('__playfulMagneticEngineLoaded', false);
            $response->assertSee('mag-click-ripple', false);
            $response->assertSee('id="mainNavbarHeader"', false);
        }
    }

    public function test_admin_layout_includes_magnetic_interaction_engine_with_sidebar_and_topbar_shielding(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // 1. Verify engine is included in admin layout for workspace content buttons
        $response->assertSee('__playfulMagneticEngineLoaded', false);

        // 2. Verify admin navigation chrome (sidebar & topbar) is strictly shielded
        $response->assertSee('.sidebar-solid, .topbar-solid, .dropdown-menu, .nav-tabs', false);
    }

    public function test_reduced_motion_is_respected_in_magnetic_engine(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('@media (prefers-reduced-motion: reduce)', false);
        $response->assertSee("prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches", false);
    }
}
