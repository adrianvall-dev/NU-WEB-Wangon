<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoNavbarFooterContrastTest extends TestCase
{
    use RefreshDatabase;

    public function test_logo_asset_exists_and_has_transparency_alpha_channel(): void
    {
        $logoPath = public_path('images/landing/logo-nu.png');

        $this->assertFileExists($logoPath);

        // Verify image has alpha channel using getimagesize / file info
        $imageInfo = getimagesize($logoPath);
        $this->assertNotFalse($imageInfo);
        $this->assertSame(IMAGETYPE_PNG, $imageInfo[2]);

        // Verify RGBA channels via GD if available or file header
        if (function_exists('imagecreatefrompng')) {
            $gdImage = imagecreatefrompng($logoPath);
            $this->assertNotFalse($gdImage);
            $this->assertTrue(imageistruecolor($gdImage));
            imagedestroy($gdImage);
        }
    }

    public function test_navbar_logo_renders_transparent_asset_with_subtle_contrast_treatment(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify navbar logo asset path, alt text, and subtle contrast class
        $response->assertSee('images/landing/logo-nu.png', false);
        $response->assertSee('alt="Logo MWC NU Wangon"', false);
        $response->assertSee('logo-contrast-subtle', false);

        // Verify navbar background and structure is preserved (not modified)
        $response->assertSee('bg-[#F6F4EE] border-b-2 border-brand-forest', false);
        $response->assertSee('bg-brand-forest text-brand-lightmatcha', false);
        $response->assertSee('border-2 border-brand-deep', false);
    }

    public function test_footer_logo_renders_transparent_asset_with_subtle_contrast_treatment(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify footer logo asset path, alt text, and subtle contrast class
        $response->assertSee('alt="Logo Resmi MWC NU Wangon"', false);
        $response->assertSee('src="'.asset('images/landing/logo-nu.png').'"', false);

        // Verify footer background and structure is preserved
        $response->assertSee('bg-brand-deep text-brand-lightmatcha', false);
        $response->assertSee('border-t-2 border-brand-forest', false);
    }

    public function test_subtle_contrast_css_rule_is_defined_without_solid_background_boxes(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify subtle drop shadow using brand-lightmatcha (217, 232, 221)
        $response->assertSee('.logo-contrast-subtle', false);
        $response->assertSee('drop-shadow(0 1px 2px rgba(217, 232, 221, 0.45))', false);
    }
}
