<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrganisasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic organization records
        Organisasi::updateOrCreate(['slug' => 'ipnu'], [
            'nama' => 'IPNU (Ikatan Pelajar Nahdlatul Ulama)',
            'singkatan' => 'IPNU',
            'deskripsi' => 'Organisasi kepemudaan pelajar putra NU.',
            'visi' => 'Terwujudnya pelajar yang berilmu, bertakwa, dan berakhlak mulia.',
            'misi' => ['Membina kader', 'Mengembangkan dakwah Ahlussunnah wal Jamaah'],
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

    public function test_public_anggota_page_displays_all_members(): void
    {
        $anggota = Anggota::factory()->create([
            'nama' => 'Zaidan Ahmad',
            'organisasi' => 'ipnu',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);

        $response = $this->get(route('anggota.index'));

        $response->assertStatus(200);
        $response->assertSee('Direktori Anggota');
        $response->assertSee('Zaidan Ahmad');
    }

    public function test_public_anggota_page_shows_organization_profile_when_filtered(): void
    {
        $org = Organisasi::where('slug', 'ipnu')->first();

        $ketua = Anggota::factory()->create([
            'nama' => 'Ahmad Fauzi',
            'organisasi' => 'ipnu',
            'jabatan' => 'Ketua',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);

        $org->update(['ketua_id' => $ketua->id]);

        $response = $this->get(route('anggota.index', ['organisasi' => 'ipnu']));

        $response->assertStatus(200);
        $response->assertSee('IPNU (Ikatan Pelajar Nahdlatul Ulama)');
        $response->assertSee('STRUKTUR KEPENGURUSAN');
        $response->assertSee('Ahmad Fauzi');
        $response->assertSee('Terwujudnya pelajar yang berilmu, bertakwa, dan berakhlak mulia.');
        $response->assertSee('Membina kader');
        $response->assertSee('DIAGRAM HIERARKI KEPENGURUSAN');
    }

    public function test_public_anggota_page_shows_clean_empty_states_when_no_visi_misi(): void
    {
        $response = $this->get(route('anggota.index', ['organisasi' => 'ippnu']));

        $response->assertStatus(200);
        $response->assertSee('Ketua belum ditentukan');
        $response->assertSee('Wakil Ketua belum ditentukan');
        $response->assertSee('Visi belum ditambahkan.');
        $response->assertSee('Misi belum ditambahkan.');
    }

    public function test_registration_prevents_multiple_active_ketua(): void
    {
        Storage::fake('public');

        $org = Organisasi::where('slug', 'ipnu')->first();

        $existingKetua = Anggota::factory()->create([
            'nama' => 'Budi Santoso',
            'organisasi' => 'ipnu',
            'jabatan' => 'Ketua',
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
        ]);

        $org->update(['ketua_id' => $existingKetua->id]);

        $response = $this->post(route('anggota.daftar.store'), [
            'nama' => 'Calon Ketua Baru',
            'nik' => '3302123456789012',
            'organisasi' => 'ipnu',
            'posisi_pendaftaran' => 'ketua',
            'no_hp' => '081234567890',
            'alamat' => 'Desa Wangon RT 01 RW 02',
            'foto' => UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('posisi_pendaftaran');
        $this->assertDatabaseMissing('anggota', [
            'nama' => 'Calon Ketua Baru',
        ]);
    }

    public function test_registration_as_ketua_succeeds_when_no_active_ketua(): void
    {
        Storage::fake('public');

        $org = Organisasi::where('slug', 'ippnu')->first();
        $this->assertNull($org->getActiveKetua());

        $response = $this->post(route('anggota.daftar.store'), [
            'nama' => 'Siti Khadijah',
            'nik' => '3302987654321098',
            'organisasi' => 'ippnu',
            'posisi_pendaftaran' => 'ketua',
            'no_hp' => '081298765432',
            'alamat' => 'Desa Klapagading RT 03 RW 01',
            'foto' => UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('anggota', [
            'nama' => 'Siti Khadijah',
            'organisasi' => 'ippnu',
            'posisi_pendaftaran' => 'ketua',
            'status_pendaftaran' => 'pending',
            'jabatan' => 'Calon Ketua',
        ]);
    }

    public function test_admin_approving_calon_ketua_assigns_as_organization_ketua(): void
    {
        $admin = $this->createAdmin();

        $calonKetua = Anggota::factory()->create([
            'nama' => 'Fathur Rahman',
            'organisasi' => 'ippnu',
            'posisi_pendaftaran' => 'ketua',
            'status_pendaftaran' => 'pending',
            'jabatan' => 'Calon Ketua',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.pendaftaran-anggota.approve', $calonKetua->id));

        $response->assertRedirect(route('admin.pendaftaran-anggota.index'));

        $calonKetua->refresh();
        $this->assertEquals('approved', $calonKetua->status_pendaftaran);
        $this->assertEquals('aktif', $calonKetua->status);
        $this->assertEquals('Ketua', $calonKetua->jabatan);

        $org = Organisasi::where('slug', 'ippnu')->first();
        $this->assertEquals($calonKetua->id, $org->ketua_id);
    }

    public function test_admin_can_manage_organisasi_visi_misi_and_structure(): void
    {
        $admin = $this->createAdmin();

        $org = Organisasi::where('slug', 'ipnu')->first();

        $ketua = Anggota::factory()->create([
            'organisasi' => 'ipnu',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.organisasi.update', $org->id), [
                'nama' => 'IPNU Cabang Wangon',
                'slug' => 'ipnu',
                'singkatan' => 'IPNU',
                'deskripsi' => 'Deskripsi baru IPNU.',
                'visi' => 'Visi baru berkarakter dan mandiri.',
                'misi' => [
                    'Menguatkan kaderisasi bertingkat',
                    'Mengembangkan jaringan ranting desa',
                ],
                'ketua_id' => $ketua->id,
            ]);

        $response->assertRedirect(route('admin.organisasi.show', $org->id));

        $org->refresh();
        $this->assertEquals('IPNU Cabang Wangon', $org->nama);
        $this->assertEquals('Visi baru berkarakter dan mandiri.', $org->visi);
        $this->assertEquals($ketua->id, $org->ketua_id);
        $this->assertCount(2, $org->misi);
    }

    public function test_all_public_pages_load_cleanly(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);

        $responseBeranda = $this->get('/beranda');
        $responseBeranda->assertRedirect('/');

        $responseAnggota = $this->get('/anggota');
        $responseAnggota->assertStatus(200);

        $responseBerita = $this->get('/berita');
        $responseBerita->assertStatus(200);

        $responseGaleri = $this->get('/galeri');
        $responseGaleri->assertStatus(200);

        $banoms = ['ansor', 'fatayat', 'muslimat', 'ipnu', 'ippnu', 'pagar_nusa'];
        foreach ($banoms as $banom) {
            $resp = $this->get('/anggota?organisasi='.$banom);
            $resp->assertStatus(200);
        }
    }

    public function test_admin_dashboard_and_organisasi_management_load_cleanly(): void
    {
        $admin = $this->createAdmin();
        $org = Organisasi::where('slug', 'ipnu')->first();

        $redirect = $this->actingAs($admin)->get('/dashboard');
        $redirect->assertRedirect(route('admin.dashboard'));

        $dashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboard->assertStatus(200);

        $index = $this->actingAs($admin)->get(route('admin.organisasi.index'));
        $index->assertStatus(200);
        $index->assertSee('Manajemen Organisasi');

        $show = $this->actingAs($admin)->get(route('admin.organisasi.show', $org->id));
        $show->assertStatus(200);
        $show->assertSee($org->nama);

        $edit = $this->actingAs($admin)->get(route('admin.organisasi.edit', $org->id));
        $edit->assertStatus(200);
        $edit->assertSee($org->nama);

        $create = $this->actingAs($admin)->get(route('admin.organisasi.create'));
        $create->assertStatus(200);
    }
}
