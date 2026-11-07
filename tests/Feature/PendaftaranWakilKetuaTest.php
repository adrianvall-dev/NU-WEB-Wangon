<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PendaftaranWakilKetuaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Organisasi::updateOrCreate(['slug' => 'ipnu'], [
            'nama' => 'IPNU (Ikatan Pelajar Nahdlatul Ulama)',
            'singkatan' => 'IPNU',
            'deskripsi' => 'Organisasi kepemudaan pelajar putra NU.',
            'visi' => 'Terwujudnya pelajar yang berilmu dan bertakwa.',
            'misi' => ['Membina kader'],
        ]);

        Organisasi::updateOrCreate(['slug' => 'ippnu'], [
            'nama' => 'IPPNU (Ikatan Pelajar Putri Nahdlatul Ulama)',
            'singkatan' => 'IPPNU',
            'deskripsi' => 'Organisasi kepemudaan pelajar putri NU.',
            'visi' => null,
            'misi' => [],
        ]);
    }

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_registration_page_shows_wakil_ketua_option(): void
    {
        $response = $this->get(route('anggota.daftar'));

        $response->assertStatus(200);
        $response->assertSee('Wakil Ketua');
        $response->assertSee('value="wakil_ketua"', false);
    }

    public function test_can_register_as_wakil_ketua_when_position_is_available(): void
    {
        Storage::fake('public');

        $payload = [
            'nama' => 'Ahmad Sholihin',
            'nik' => '3302111122334455',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sindang No. 12',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'foto' => UploadedFile::fake()->create('foto_formal.jpg', 500, 'image/jpeg'),
        ];

        $response = $this->post(route('anggota.daftar.store'), $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('anggota', [
            'nama' => 'Ahmad Sholihin',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'jabatan' => 'Calon Wakil Ketua',
            'status_pendaftaran' => 'pending',
        ]);

        $anggota = Anggota::where('nik', '3302111122334455')->first();
        $this->assertNotNull($anggota);
        $response->assertRedirect(route('anggota.status', $anggota->kode_pendaftaran));
    }

    public function test_registration_as_wakil_ketua_fails_if_active_wakil_ketua_already_exists(): void
    {
        Storage::fake('public');

        $org = Organisasi::where('slug', 'ipnu')->first();
        $existingWakil = Anggota::factory()->create([
            'nama' => 'Bambang Subroto',
            'organisasi' => 'ipnu',
            'jabatan' => 'Wakil Ketua',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);
        $org->update(['wakil_ketua_id' => $existingWakil->id]);

        $payload = [
            'nama' => 'Calon Baru',
            'nik' => '3302998877665544',
            'no_hp' => '081234567899',
            'alamat' => 'Jl. Sindang No. 99',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'foto' => UploadedFile::fake()->create('foto.jpg', 500, 'image/jpeg'),
        ];

        $response = $this->from(route('anggota.daftar'))->post(route('anggota.daftar.store'), $payload);

        $response->assertSessionHasErrors(['posisi_pendaftaran']);
        $this->assertDatabaseMissing('anggota', [
            'nik' => '3302998877665544',
        ]);
    }

    public function test_status_page_displays_calon_wakil_ketua_badge(): void
    {
        $anggota = Anggota::factory()->create([
            'nama' => 'Calon Wakil Terdaftar',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'jabatan' => 'Calon Wakil Ketua',
            'status_pendaftaran' => 'pending',
            'kode_pendaftaran' => 'REG-WAKIL-1234',
        ]);

        $response = $this->get(route('anggota.status', $anggota->kode_pendaftaran));

        $response->assertStatus(200);
        $response->assertSee('Calon Wakil Ketua');
    }

    public function test_admin_index_displays_wakil_ketua_filter_and_badge(): void
    {
        $admin = $this->createAdmin();

        $anggotaWakil = Anggota::factory()->create([
            'nama' => 'Siti Aminah',
            'organisasi' => 'ippnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'jabatan' => 'Calon Wakil Ketua',
            'status_pendaftaran' => 'pending',
            'kode_pendaftaran' => 'REG-WK-001',
        ]);

        $anggotaBiasa = Anggota::factory()->create([
            'nama' => 'Nur Hasanah',
            'organisasi' => 'ippnu',
            'posisi_pendaftaran' => 'anggota',
            'jabatan' => 'Anggota',
            'status_pendaftaran' => 'pending',
            'kode_pendaftaran' => 'REG-AG-002',
        ]);

        // Access index without filter
        $response = $this->actingAs($admin)->get(route('admin.pendaftaran-anggota.index'));
        $response->assertStatus(200);
        $response->assertSee('Calon Wakil Ketua');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Nur Hasanah');

        // Filter by wakil_ketua
        $filterResponse = $this->actingAs($admin)->get(route('admin.pendaftaran-anggota.index', [
            'posisi' => 'wakil_ketua',
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Siti Aminah');
        $filterResponse->assertDontSee('Nur Hasanah');
    }

    public function test_admin_show_page_displays_calon_wakil_ketua_badge_and_notice(): void
    {
        $admin = $this->createAdmin();

        $anggota = Anggota::factory()->create([
            'nama' => 'Rahmat Hidayat',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'jabatan' => 'Calon Wakil Ketua',
            'status_pendaftaran' => 'pending',
            'kode_pendaftaran' => 'REG-RH-999',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pendaftaran-anggota.show', $anggota->id));

        $response->assertStatus(200);
        $response->assertSee('Calon Wakil Ketua');
        $response->assertSee('Pemberitahuan Calon Wakil Ketua');
    }

    public function test_admin_approving_wakil_ketua_updates_organization_and_member_position(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ipnu')->first();

        // Old wakil ketua
        $oldWakil = Anggota::factory()->create([
            'nama' => 'Wakil Lama',
            'organisasi' => 'ipnu',
            'jabatan' => 'Wakil Ketua',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);
        $org->update(['wakil_ketua_id' => $oldWakil->id]);

        // New applicant for wakil ketua
        $applicant = Anggota::factory()->create([
            'nama' => 'Wakil Baru',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
            'jabatan' => 'Calon Wakil Ketua',
            'status_pendaftaran' => 'pending',
            'kode_pendaftaran' => 'REG-NEW-WK',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.pendaftaran-anggota.approve', $applicant->id));

        $response->assertRedirect(route('admin.pendaftaran-anggota.index'));

        // Verify new applicant is approved and assigned as Wakil Ketua
        $applicant->refresh();
        $this->assertEquals('approved', $applicant->status_pendaftaran);
        $this->assertEquals('aktif', $applicant->status);
        $this->assertEquals('Wakil Ketua', $applicant->jabatan);

        // Verify organization's wakil_ketua_id is updated to applicant
        $org->refresh();
        $this->assertEquals($applicant->id, $org->wakil_ketua_id);

        // Verify previous wakil ketua was demoted to Anggota
        $oldWakil->refresh();
        $this->assertEquals('Anggota', $oldWakil->jabatan);
    }

    public function test_applicant_can_update_to_wakil_ketua_during_perbaiki(): void
    {
        $applicant = Anggota::factory()->create([
            'nama' => 'Pendaftar Perbaikan',
            'nik' => '3302778899001122',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'anggota',
            'jabatan' => 'Anggota',
            'status_pendaftaran' => 'rejected',
            'alasan_penolakan' => 'Foto buram',
            'kode_pendaftaran' => 'REG-FIX-111',
        ]);

        $response = $this->get(route('anggota.perbaiki', $applicant->kode_pendaftaran));
        $response->assertStatus(200);
        $response->assertSee('Wakil Ketua');

        $updateResponse = $this->post(route('anggota.perbaiki.update', $applicant->kode_pendaftaran), [
            'nama' => 'Pendaftar Perbaikan Terkoreksi',
            'nik' => '3302778899001122',
            'no_hp' => '081234567800',
            'alamat' => 'Jl. Sindang Indah No. 5',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'wakil_ketua',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $applicant->refresh();
        $this->assertEquals('pending', $applicant->status_pendaftaran);
        $this->assertEquals('wakil_ketua', $applicant->posisi_pendaftaran);
        $this->assertEquals('Calon Wakil Ketua', $applicant->jabatan);
        $this->assertNull($applicant->alasan_penolakan);
    }
}
