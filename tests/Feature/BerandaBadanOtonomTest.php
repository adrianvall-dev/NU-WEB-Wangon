<?php

namespace Tests\Feature;

use App\Models\Organisasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BerandaBadanOtonomTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_displays_real_organization_count_and_avatars_with_plus_n(): void
    {
        // Secara default, migrasi telah memasukkan 6 organisasi: ansor, fatayat, muslimat, ipnu, ippnu, pagar_nusa
        $this->assertEquals(6, Organisasi::count());

        $response = $this->get('/');

        $response->assertStatus(200);

        // Angka harus 6 (sesuai DB), bukan 12+
        $response->assertSee('6 Badan Otonom &amp; Ranting', false);
        $response->assertDontSee('12+ Badan Otonom &amp; Ranting', false);
        $response->assertDontSee('12+');

        // Teks deskripsi umum tanpa klaim palsu
        $response->assertSee('Badan otonom dan lembaga di lingkungan organisasi');
        $response->assertDontSee('Serentak aktif melayani warga Nahdliyin se-Kecamatan Wangon');

        // Avatar inisial 3 organisasi pertama (GA: GP Ansor, FN: Fatayat NU, MN: Muslimat NU)
        $response->assertSee('GA');
        $response->assertSee('FN');
        $response->assertSee('MN');

        // Sisa 3 organisasi ditampilkan sebagai +3
        $response->assertSee('+3');

        // Card 03 juga menampilkan angka 6 secara konsisten
        $response->assertSee('data-count="6"', false);
    }

    public function test_beranda_displays_exact_count_without_plus_n_when_three_or_fewer_orgs(): void
    {
        // Sisakan hanya 2 organisasi
        Organisasi::whereNotIn('slug', ['ansor', 'fatayat'])->delete();
        $this->assertEquals(2, Organisasi::count());

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('2 Badan Otonom &amp; Ranting', false);
        $response->assertDontSee('organisasi lainnya');
        $response->assertSee('data-count="2"', false);
    }

    public function test_beranda_displays_clean_zero_state_when_database_has_no_organisasi(): void
    {
        Organisasi::query()->delete();
        $this->assertEquals(0, Organisasi::count());

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('0 Badan Otonom &amp; Ranting', false);
        $response->assertSee('Belum ada badan otonom terdaftar di sistem');
        $response->assertDontSee('12+');
        $response->assertDontSee('Serentak aktif melayani warga Nahdliyin se-Kecamatan Wangon');
        $response->assertSee('data-count="0"', false);
    }

    public function test_beranda_displays_logo_image_if_organization_has_uploaded_logo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('ansor_logo.png', 50, 'image/png');
        $path = $file->store('organisasi/logo', 'public');

        $ansor = Organisasi::where('slug', 'ansor')->first();
        $ansor->update(['logo' => $path]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('storage/'.$path);
    }
}
