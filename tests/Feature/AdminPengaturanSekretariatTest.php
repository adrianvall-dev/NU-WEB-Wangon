<?php

namespace Tests\Feature;

use App\Models\KartuLayanan;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPengaturanSekretariatTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_guest_cannot_access_pengaturan_sekretariat_and_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.pengaturan.sekretariat'));
        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_pengaturan_sekretariat(): void
    {
        $response = $this->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'TEST SECTION',
            'nama_kantor' => 'Kantor Percobaan',
            'alamat' => 'Jl. Percobaan',
        ]);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_pengaturan_sekretariat_form(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.pengaturan.sekretariat'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Layanan &amp; Sekretariat', false);
        $response->assertSee('name="label_section"', false);
        $response->assertSee('name="nama_kantor"', false);
        $response->assertSee('name="alamat"', false);
        $response->assertSee('name="whatsapp"', false);
        $response->assertSee('name="google_maps_url"', false);
        $response->assertSee('name="jam_operasional_hari"', false);
        $response->assertSee('Jam Operasional');
        $response->assertSee('Layanan KARTANU');
        $response->assertSee('Ruang Rapat Banom');
    }

    public function test_admin_can_update_office_details_and_contacts(): void
    {
        $admin = $this->createAdmin();

        $updateData = [
            'label_section' => 'LAYANAN TERPADU NU WANGON',
            'nama_kantor' => 'Gedung Pusat Dakwah MWC NU Wangon',
            'alamat' => 'Jl. Gerilya No. 45, Dusun Sindurjan Baru',
            'desa' => 'Wangon Lor',
            'kecamatan' => 'Wangon',
            'kabupaten' => 'Banyumas',
            'provinsi' => 'Jawa Tengah',
            'kode_pos' => '53176',
            'telepon' => '0821-9988-7766',
            'whatsapp' => '082199887766',
            'email' => 'sekretariat.mwc@nuwangon.or.id',
            'google_maps_url' => 'https://maps.google.com/?q=MWC+NU+Wangon+Baru',
            'jam_operasional_hari' => 'Senin - Sabtu',
            'jam_buka' => '08.30',
            'jam_tutup' => '15.30',
            'jam_operasional_status' => 'Buka Setiap Hari Kerja',
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.pengaturan.sekretariat.update'), $updateData);

        $response->assertRedirect(route('admin.pengaturan.sekretariat'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengaturan', [
            'label_section' => 'LAYANAN TERPADU NU WANGON',
            'nama_kantor' => 'Gedung Pusat Dakwah MWC NU Wangon',
            'whatsapp' => '082199887766',
            'email' => 'sekretariat.mwc@nuwangon.or.id',
            'google_maps_url' => 'https://maps.google.com/?q=MWC+NU+Wangon+Baru',
            'jam_operasional_hari' => 'Senin - Sabtu',
        ]);
    }

    public function test_whatsapp_number_is_normalized_and_rendered_on_public_homepage(): void
    {
        $admin = $this->createAdmin();

        // 1. Update with Indonesian standard mobile starting with 08
        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon - Ajibarang',
            'whatsapp' => '0857-1234-5678',
            'google_maps_url' => 'https://maps.google.com/?q=Wangon+Banyumas',
        ]);

        // 2. Public homepage should format into https://wa.me/6285712345678
        $public = $this->get(route('home'));
        $public->assertStatus(200);
        $public->assertSee('https://wa.me/6285712345678', false);
        $public->assertSee('Hubungi Sekretariat');
    }

    public function test_google_maps_url_is_dynamic_on_public_homepage(): void
    {
        $admin = $this->createAdmin();

        $customMaps = 'https://maps.google.com/?q=Kantor+MWC+Wangon+Khusus';

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon',
            'google_maps_url' => $customMaps,
        ]);

        $public = $this->get(route('home'));
        $public->assertStatus(200);
        $public->assertSee($customMaps, false);
        $public->assertSee('Buka Petunjuk Arah Maps');
    }

    public function test_buttons_are_disabled_safely_when_maps_or_whatsapp_are_not_provided(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon',
            'whatsapp' => null,
            'google_maps_url' => null,
        ]);

        $public = $this->get(route('home'));
        $public->assertStatus(200);
        // Verify disabled buttons are present without broken link targets
        $public->assertSee('cursor-not-allowed', false);
        $public->assertSee('Buka Petunjuk Arah Maps');
        $public->assertSee('Hubungi Sekretariat');
    }

    public function test_admin_can_manage_service_cards_add_edit_and_delete(): void
    {
        $admin = $this->createAdmin();

        // 1. Add a new service card via storeCard
        $storeResponse = $this->actingAs($admin)->post(route('admin.pengaturan.sekretariat.kartu.store'), [
            'judul' => 'Layanan ZISWAF LAZISNU',
            'deskripsi' => 'Konsultasi Zakat & Sedekah di Lantai 1',
            'urutan' => 4,
            'is_active' => true,
        ]);
        $storeResponse->assertRedirect(route('admin.pengaturan.sekretariat'));
        $this->assertDatabaseHas('kartu_layanans', [
            'judul' => 'Layanan ZISWAF LAZISNU',
            'deskripsi' => 'Konsultasi Zakat & Sedekah di Lantai 1',
        ]);

        // 2. Verify new card is displayed on homepage
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertSee('Layanan ZISWAF LAZISNU');
        $homeResponse->assertSee('Konsultasi Zakat & Sedekah di Lantai 1');

        // 3. Delete the card
        $newCard = KartuLayanan::where('judul', 'Layanan ZISWAF LAZISNU')->first();
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.pengaturan.sekretariat.kartu.destroy', $newCard->id));
        $deleteResponse->assertRedirect(route('admin.pengaturan.sekretariat'));
        $this->assertDatabaseMissing('kartu_layanans', [
            'id' => $newCard->id,
        ]);
    }

    public function test_public_homepage_preserves_strict_design_and_layout(): void
    {
        $pengaturan = Pengaturan::ambil();
        $pengaturan->update([
            'label_section' => 'LAYANAN & SEKRETARIAT UTAMA',
            'nama_kantor' => 'Kantor Pengurus MWC Wangon',
            'alamat' => 'Jl. Raya Wangon - Ajibarang No. 12',
            'desa' => 'Wangon',
            'kecamatan' => 'Wangon',
            'kabupaten' => 'Banyumas',
            'provinsi' => 'Jawa Tengah',
            'kode_pos' => '53176',
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Verify section container and design classes are 100% preserved
        $response->assertSee('id="lokasi"', false);
        $response->assertSee('bg-brand-cream relative overflow-hidden', false);
        $response->assertSee('pixel-grid-blueprint', false);
        $response->assertSee('floating-reveal floating-frame-card', false);
        $response->assertSee('shadow-solid-lg', false);
        $response->assertSee('border-2 border-brand-forest', false);

        // Verify dynamic values from database are shown
        $response->assertSee('LAYANAN &amp; SEKRETARIAT UTAMA', false);
        $response->assertSee('Kantor Pengurus MWC Wangon');
        $response->assertSee('Jl. Raya Wangon - Ajibarang No. 12');
    }
}
