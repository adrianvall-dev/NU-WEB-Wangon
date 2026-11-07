<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaCard3DPhysicalFlipTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_index_renders_cards_with_3d_physical_flip_engine_and_accessibility(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'Ahmad Fuad',
            'organisasi' => 'ansor',
            'jabatan' => 'Ketua Ranting',
            'status' => 'aktif',
            'kode_verifikasi' => 'VRF-ANS-001',
            'nomor_anggota' => 'NU-ANS-2026-0001',
        ]);

        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);

        // 1. Structure preservation: wrapper, rotor, front & back
        $response->assertSee('card-3d-wrapper', false);
        $response->assertSee('card-3d-inner', false);
        $response->assertSee('card-face card-front', false);
        $response->assertSee('card-face card-back', false);

        // 2. Physical Engine CSS & elements: perspective, touch-action: pan-y, shadow, glare
        $response->assertSee('perspective: 1200px', false);
        $response->assertSee('touch-action: pan-y', false);
        $response->assertSee('.card-3d-shadow', false);
        $response->assertSee('.card-face-glare', false);
        $response->assertSee('--fc-gx', false);
        $response->assertSee('--fc-gy', false);
        $response->assertSee('--fc-sheen', false);

        // 3. Engine JS loaded
        $response->assertSee('__card3DFlipEngineLoaded', false);
        $response->assertSee('Card3DFlipEngine', false);
        $response->assertSee('createSpring', false);

        // 4. Content preservation
        $response->assertSee('Ahmad Fuad');
        $response->assertSee('Ketua Ranting');
        $response->assertSee('NU-ANS-2026-0001');
        $response->assertSee('VRF-ANS-001');
    }

    public function test_standalone_kartu_page_renders_3d_physical_engine_with_toggle_card_flip(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'Hj. Fatimah',
            'organisasi' => 'muslimat',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'kode_pendaftaran' => 'REG-MUS-777',
            'nomor_anggota' => 'NU-MUS-2026-0777',
            'kode_verifikasi' => 'VRF-MUS-777',
        ]);

        $response = $this->get(route('anggota.kartu', $kader->kode_pendaftaran));

        $response->assertStatus(200);

        // 1. Standalone card wrapper & inner
        $response->assertSee('card-3d-wrapper');
        $response->assertSee('card-3d-inner');
        $response->assertSee('card-front');
        $response->assertSee('card-back');

        // 2. 3D engine integration
        $response->assertSee('Card3DFlipEngine');
        $response->assertSee('toggleCardFlip');
        $response->assertSee('touch-action: pan-y', false);
    }
}
