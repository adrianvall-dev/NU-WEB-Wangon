<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrganisasiLayoutAndPhotoFixTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Organisasi::updateOrCreate(['slug' => 'ansor'], [
            'nama' => 'Gerakan Pemuda Ansor',
            'singkatan' => 'GP Ansor',
            'deskripsi' => 'Badan otonom pemuda NU.',
            'visi' => 'Menjadi pemuda berakhlak dan mandiri.',
            'misi' => ['Kaderisasi berkala', 'Penguatan ekonomi umat'],
        ]);
    }

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_organisasi_detail_with_properly_constrained_member_photo_thumbnails(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ansor')->first();

        // Anggota with photo
        $anggotaWithFoto = Anggota::factory()->create([
            'nama' => 'Ahmad Foto Besar',
            'organisasi' => 'ansor',
            'foto' => 'anggota/fake_photo_715x1024.jpg',
            'status' => 'aktif',
        ]);

        // Anggota without photo
        $anggotaNoFoto = Anggota::factory()->create([
            'nama' => 'Budi Tanpa Foto',
            'organisasi' => 'ansor',
            'foto' => null,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.organisasi.show', $org->id));

        $response->assertStatus(200);
        $response->assertSee('table-avatar-thumb', false);
        $response->assertSee('Ahmad Foto Besar');
        $response->assertSee('Budi Tanpa Foto');
        $response->assertSee('onerror="this.style.display=\'none\';', false);
    }

    public function test_admin_organisasi_detail_renders_ketua_and_wakil_ketua_cards_with_bounded_photo_dimensions(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ansor')->first();

        $ketua = Anggota::factory()->create([
            'nama' => 'H. Salman Ketua',
            'organisasi' => 'ansor',
            'jabatan' => 'Ketua',
            'foto' => 'anggota/ketua_photo.jpg',
            'status' => 'aktif',
            'nomor_anggota' => 'NU-ANS-001',
        ]);

        $wakil = Anggota::factory()->create([
            'nama' => 'Ust. Yusuf Wakil',
            'organisasi' => 'ansor',
            'jabatan' => 'Wakil Ketua',
            'foto' => 'anggota/wakil_photo.jpg',
            'status' => 'aktif',
            'nomor_anggota' => 'NU-ANS-002',
        ]);

        $org->update([
            'ketua_id' => $ketua->id,
            'wakil_ketua_id' => $wakil->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.organisasi.show', $org->id));

        $response->assertStatus(200);
        $response->assertSee('card-pimpinan-avatar', false);
        $response->assertSee('H. Salman Ketua');
        $response->assertSee('Ust. Yusuf Wakil');
        $response->assertSee('NU-ANS-001');
        $response->assertSee('NU-ANS-002');
    }

    public function test_empty_ketua_and_wakil_ketua_render_graceful_empty_states_without_error(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ansor')->first();

        $org->update([
            'ketua_id' => null,
            'wakil_ketua_id' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.organisasi.show', $org->id));

        $response->assertStatus(200);
        $response->assertSee('Ketua belum ditentukan');
        $response->assertSee('Wakil Ketua belum ditentukan');
        $response->assertSee('Pilih Ketua');
        $response->assertSee('Pilih Wakil Ketua');
    }

    public function test_updating_visi_and_misi_preserves_layout_stability_and_stores_data_properly(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ansor')->first();

        $newVisi = 'Menjadi pilar peradaban pemuda Nahdliyin yang berdikari, tangguh, dan menjunjung tinggi ukhuwah wathaniyah.';
        $newMisi = [
            'Mengembangkan dakwah digital ramah generasi muda.',
            'Mendirikan balai latihan kerja dan inkubasi wirausaha santri.',
            'Memperkuat kesiapsiagaan tanggap bencana banser.',
        ];

        $updateResponse = $this->actingAs($admin)->put(route('admin.organisasi.update', $org->id), [
            'nama' => $org->nama,
            'singkatan' => $org->singkatan,
            'slug' => $org->slug,
            'visi' => $newVisi,
            'misi' => $newMisi,
        ]);

        $updateResponse->assertRedirect(route('admin.organisasi.show', $org->id));

        $org->refresh();
        $this->assertEquals($newVisi, $org->visi);
        $this->assertCount(3, $org->misi);
        $this->assertEquals($newMisi[0], $org->misi[0]);

        $showResponse = $this->actingAs($admin)->get(route('admin.organisasi.show', $org->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($newVisi);
        $showResponse->assertSee($newMisi[0]);
        $showResponse->assertSee($newMisi[1]);
        $showResponse->assertSee($newMisi[2]);
    }

    public function test_updating_ketua_and_wakil_ketua_syncs_member_organization_and_demotes_previous_leaders(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ansor')->first();

        $oldKetua = Anggota::factory()->create([
            'nama' => 'Ketua Demisioner',
            'organisasi' => 'ansor',
            'jabatan' => 'Ketua',
            'status' => 'aktif',
        ]);
        $org->update(['ketua_id' => $oldKetua->id]);

        $newKetuaCandidate = Anggota::factory()->create([
            'nama' => 'Ketua Baru Terpilih',
            'organisasi' => 'ansor',
            'jabatan' => 'Anggota',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.organisasi.update', $org->id), [
            'nama' => $org->nama,
            'singkatan' => $org->singkatan,
            'slug' => $org->slug,
            'ketua_id' => $newKetuaCandidate->id,
        ]);

        $response->assertRedirect(route('admin.organisasi.show', $org->id));

        $org->refresh();
        $this->assertEquals($newKetuaCandidate->id, $org->ketua_id);

        $newKetuaCandidate->refresh();
        $this->assertEquals('Ketua', $newKetuaCandidate->jabatan);
        $this->assertEquals('ansor', $newKetuaCandidate->organisasi);

        $oldKetua->refresh();
        $this->assertEquals('Anggota', $oldKetua->jabatan);
    }

    public function test_admin_organisasi_index_displays_bounded_logos_and_pimpinan_avatars(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ansor')->first();

        $ketua = Anggota::factory()->create([
            'nama' => 'Ketua Ansor',
            'organisasi' => 'ansor',
            'jabatan' => 'Ketua',
            'status' => 'aktif',
        ]);
        $org->update(['ketua_id' => $ketua->id]);

        $response = $this->actingAs($admin)->get(route('admin.organisasi.index'));

        $response->assertStatus(200);
        $response->assertSee('table-avatar-thumb', false);
        $response->assertSee('Gerakan Pemuda Ansor');
        $response->assertSee('Ketua Ansor');
    }
}
