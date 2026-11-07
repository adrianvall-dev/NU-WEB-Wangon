<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaSharedMorphTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_page_renders_cards_with_shared_identities_and_morph_attributes(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'Muhammad Rifqi',
            'organisasi' => 'ansor',
            'jabatan' => 'Sekretaris PAC Ansor',
            'status' => 'aktif',
            'no_hp' => '081298765432',
            'alamat' => 'Wangon Lor RT 01 RW 02',
        ]);

        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);

        // 1. Unique Shared Identity on Card
        $response->assertSee('id="member-card-'.$kader->id.'"', false);
        $response->assertSee('data-shared-card="member-card-'.$kader->id.'"', false);
        $response->assertSee('data-open-member="'.$kader->id.'"', false);
        $response->assertSee('data-shared-image="member-image-'.$kader->id.'"', false);
        $response->assertSee('view-transition-name: member-card-'.$kader->id, false);
        $response->assertSee('view-transition-name: member-image-'.$kader->id, false);

        // 2. Member card is visually clickable
        $response->assertSee('member-card-item', false);
        $response->assertSee('cursor-pointer', false);
    }

    public function test_anggota_card_renders_3d_flip_two_sided_card_with_logo_nu_and_barcode(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'Fathur Rahman',
            'organisasi' => 'ipnu',
            'jabatan' => 'Kader IPNU',
            'status' => 'aktif',
            'kode_verifikasi' => 'VRF-IPNU-9999',
            'nomor_anggota' => 'NU-IPN-2026-0099',
        ]);

        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);

        // 1. 3D Card Architecture (Front and Back Faces)
        $response->assertSee('card-3d-wrapper', false);
        $response->assertSee('card-3d-inner', false);
        $response->assertSee('card-face card-front', false);
        $response->assertSee('card-face card-back', false);
        $response->assertSee('data-flip-target="member-flip-'.$kader->id.'"', false);

        // 2. Official NU Logo is present on card
        $response->assertSee('images/logo-nu-card.webp', false);

        // 3. Barcode and Verification QR on Back of Card
        $response->assertSee('QR Verifikasi', false);
        $response->assertSee(urlencode(route('verifikasi.show', 'VRF-IPNU-9999')), false);
        $response->assertSee('VRF-IPNU-9999', false);
        $response->assertSee('E-KARTANU RESMI', false);
    }

    public function test_anggota_page_renders_expanded_detail_with_matching_shared_identities_and_controls(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'Siti Aisyah',
            'organisasi' => 'fatayat',
            'jabatan' => 'Kader Fatayat',
            'status' => 'aktif',
            'no_hp' => '085712345678',
            'alamat' => 'Desa Banteran RT 03 RW 01',
        ]);

        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);

        // 1. Detail Dialog Shared Identity
        $response->assertSee('id="modalProfil'.$kader->id.'"', false);
        $response->assertSee('data-detail-dialog="'.$kader->id.'"', false);
        $response->assertSee('data-detail-image="'.$kader->id.'"', false);
        $response->assertSee('data-shared-card="member-card-'.$kader->id.'"', false);
        $response->assertSee('data-shared-image="member-image-'.$kader->id.'"', false);

        // 2. Overlay / Backdrop & Close Triggers
        $response->assertSee('member-morph-backdrop', false);
        $response->assertSee('data-close-member="true"', false);

        // 3. Member Information Presence in Detail Dialog
        $response->assertSee('Siti Aisyah');
        $response->assertSee('Kader Fatayat');
        $response->assertSee('085712345678');
        $response->assertSee('Desa Banteran RT 03 RW 01');
        $response->assertSee('Aktif &amp; Terverifikasi', false);

        // 4. Two-sided Card in Detail View
        $response->assertSee('data-flip-target="detail-flip-'.$kader->id.'"', false);
    }

    public function test_morph_profile_engine_script_and_spring_physics_are_present(): void
    {
        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);

        // 1. Engine loaded guard
        $response->assertSee('__anggotaMorphEngineLoaded', false);

        // 2. Spring curve and duration configuration
        $response->assertSee('cubic-bezier(0.34, 1.25, 0.64, 1)', false);
        $response->assertSee('OPEN_DURATION = 380', false);
        $response->assertSee('CLOSE_DURATION = 320', false);

        // 3. View Transitions pseudo-element styling
        $response->assertSee('::view-transition-image-pair(*)', false);
        $response->assertSee('mix-blend-mode: normal', false);

        // 4. Accessibility & Reduced Motion Handling
        $response->assertSee('@media (prefers-reduced-motion: reduce)', false);
    }

    public function test_search_and_filters_preserve_morph_identities_on_results(): void
    {
        $kaderAnsor = Anggota::factory()->create([
            'nama' => 'Zainul Abidin',
            'organisasi' => 'ansor',
            'status' => 'aktif',
        ]);

        $kaderMuslimat = Anggota::factory()->create([
            'nama' => 'Hj. Aminah',
            'organisasi' => 'muslimat',
            'status' => 'aktif',
        ]);

        // Filter by organisasi=ansor
        $responseFiltered = $this->get(route('anggota.index', ['organisasi' => 'ansor']));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee('member-card-'.$kaderAnsor->id);
        $responseFiltered->assertSee('modalProfil'.$kaderAnsor->id);
        $responseFiltered->assertDontSee('member-card-'.$kaderMuslimat->id);

        // Search by name
        $responseSearch = $this->get(route('anggota.index', ['search' => 'Aminah']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('member-card-'.$kaderMuslimat->id);
        $responseSearch->assertSee('modalProfil'.$kaderMuslimat->id);
        $responseSearch->assertDontSee('member-card-'.$kaderAnsor->id);
    }

    public function test_kartu_website_standalone_renders_flip_card_front_and_back(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'Gus Ahmad Dahlan',
            'organisasi' => 'ansor',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'kode_pendaftaran' => 'REG-TEST999',
            'nomor_anggota' => 'NU-ANS-2026-9999',
            'no_hp' => '081234567890',
            'kode_verifikasi' => 'VRF-ABC123XYZ',
        ]);

        $response = $this->get(route('anggota.kartu', $kader->kode_pendaftaran));

        $response->assertStatus(200);
        $response->assertSee('images/logo-nu-card.webp');
        $response->assertSee('card-3d-wrapper');
        $response->assertSee('card-3d-inner');
        $response->assertSee('card-front');
        $response->assertSee('card-back');
        $response->assertSee('toggleCardFlip');
        $response->assertSee('Gus Ahmad Dahlan');
        $response->assertSee('NU-ANS-2026-9999');
        $response->assertSee($kader->no_hp_masked);
        $response->assertSee('VRF-ABC123XYZ');
        $response->assertSee(urlencode(route('verifikasi.show', 'VRF-ABC123XYZ')));
    }

    public function test_kartu_pdf_download_generates_exact_two_page_print_safe_card(): void
    {
        $kader = Anggota::factory()->create([
            'nama' => 'K.H. Hasyim Asyari',
            'organisasi' => 'ansor',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'kode_pendaftaran' => 'REG-PDF001',
            'nomor_anggota' => 'NU-ANS-2026-0001',
            'no_hp' => '081234567890',
            'kode_verifikasi' => 'VRF-PDFTEST99',
        ]);

        $response = $this->get(route('anggota.kartu.pdf', $kader->kode_pendaftaran));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        // Verify PDF rendering creates EXACTLY 2 pages
        $html = view('anggota.kartu-pdf', ['anggota' => $kader])->render();
        $this->assertStringNotContainsString('BALIK KE SISI BARCODE', $html);
        $this->assertStringNotContainsString('BALIK KE SISI DEPAN', $html);
        $this->assertStringContainsString('K.H. Hasyim Asyari', $html);
        $this->assertStringContainsString('NU-ANS-2026-0001', $html);
        $this->assertStringContainsString('VRF-PDFTEST99', $html);

        $pdf = Pdf::loadHTML($html)->setPaper([0, 0, 202, 278]);
        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        $this->assertSame(2, $dompdf->getCanvas()->get_page_count());
    }
}
