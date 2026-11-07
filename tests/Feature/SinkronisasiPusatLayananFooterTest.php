<?php

namespace Tests\Feature;

use App\Models\KartuLayanan;
use App\Models\News;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SinkronisasiPusatLayananFooterTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_office_address_update_is_synchronized_across_beranda_and_footer(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor Pengurus MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Gedung Dakwah NU, Jl. KH Wahid Hasyim No. 99',
            'desa' => 'Wangon Lor',
            'kecamatan' => 'Wangon',
            'kabupaten' => 'Banyumas',
            'provinsi' => 'Jawa Tengah',
            'kode_pos' => '53176',
            'whatsapp' => '081234567890',
            'google_maps_url' => 'https://maps.google.com/?q=Wangon+Banyumas',
        ]);

        $home = $this->get(route('home'));
        $home->assertStatus(200);

        $pengaturan = Pengaturan::ambil();
        $expectedAddress = $pengaturan->alamat_lengkap_formatted;

        // Beranda section Pusat Layanan harus menampilkan alamat terbaru
        $home->assertSee('Kantor Pengurus MWC Nahdlatul Ulama Wangon');
        $home->assertSee($expectedAddress);

        // Footer harus menampilkan nama kantor dan alamat yang sama persis
        $home->assertSee($expectedAddress);
        $this->assertStringContainsString('Gedung Dakwah NU, Jl. KH Wahid Hasyim No. 99', $expectedAddress);
    }

    public function test_whatsapp_number_update_is_synchronized_across_beranda_and_footer_buttons_and_links(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon - Ajibarang',
            'whatsapp' => '0877-5544-3322',
            'google_maps_url' => 'https://maps.google.com/?q=Wangon+Banyumas',
        ]);

        $home = $this->get(route('home'));
        $home->assertStatus(200);

        $expectedWaUrl = 'https://wa.me/6287755443322';

        // 1. Tombol CTA Beranda "Hubungi Sekretariat"
        $home->assertSee('href="'.$expectedWaUrl.'"', false);
        $home->assertSee('Hubungi Sekretariat');

        // 2. Box kontak pendaftaran di Beranda
        $home->assertSee('0877-5544-3322');

        // 3. Ikon media sosial WhatsApp di Footer
        $home->assertSee('title="WhatsApp Sekretariat"', false);

        // 4. Nomor kontak pada kolom Sekretariat di Footer
        $home->assertSee('WhatsApp:');
        $home->assertSee('0877-5544-3322');
    }

    public function test_google_maps_url_update_is_synchronized_across_beranda_and_footer(): void
    {
        $admin = $this->createAdmin();

        $customMaps = 'https://maps.google.com/?q=Lokasi+Sekretariat+Baru+Wangon';

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon - Ajibarang',
            'google_maps_url' => $customMaps,
        ]);

        $home = $this->get(route('home'));
        $home->assertStatus(200);

        // 1. Tombol Maps Beranda "Buka Petunjuk Arah Maps"
        $home->assertSee('href="'.$customMaps.'"', false);
        $home->assertSee('Buka Petunjuk Arah Maps');

        // 2. Tautan alamat di Footer yang mengarah ke Google Maps
        $home->assertSee('title="Buka Petunjuk Arah di Google Maps"', false);
    }

    public function test_operational_hours_update_is_synchronized_across_beranda_cards_and_footer(): void
    {
        $admin = $this->createAdmin();

        KartuLayanan::create([
            'judul' => 'Jam Operasional',
            'deskripsi' => 'Senin - Ahad, 08.00 - 16.00',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon - Ajibarang',
            'jam_operasional_hari' => 'Senin - Jumat',
            'jam_buka' => '07.30',
            'jam_tutup' => '15.30',
            'jam_operasional_status' => 'Pelayanan Penuh',
        ]);

        $home = $this->get(route('home'));
        $home->assertStatus(200);

        $expectedJam = 'Senin - Jumat, 07.30 - 15.30';

        // Beranda card Jam Operasional harus menampilkan jam terbaru
        $home->assertSee('Jam Operasional');
        $home->assertSee($expectedJam);

        // Footer Jam Layanan harus menampilkan jam yang sama
        $home->assertSee('Jam Layanan: '.$expectedJam);
    }

    public function test_email_update_is_synchronized_across_beranda_and_footer(): void
    {
        $admin = $this->createAdmin();

        $newEmail = 'layanan.terpadu@nuwangon.or.id';

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => 'Jl. Raya Wangon - Ajibarang',
            'email' => $newEmail,
        ]);

        $home = $this->get(route('home'));
        $home->assertStatus(200);

        // Beranda email box
        $home->assertSee('mailto:'.$newEmail, false);
        $home->assertSee($newEmail);

        // Footer email
        $home->assertSee('mailto:'.$newEmail, false);
    }

    public function test_footer_on_other_public_pages_displays_latest_synchronized_data(): void
    {
        $admin = $this->createAdmin();

        $customOffice = 'Gedung Sekretariat Terpadu NU Wangon';
        $customWa = '0813-9900-1122';
        $customEmail = 'sekretariat.pusat@nuwangon.or.id';

        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => $customOffice,
            'alamat' => 'Jl. Raya Barat No. 88, Desa Wangon',
            'whatsapp' => $customWa,
            'email' => $customEmail,
        ]);

        // Buat dummy berita agar /berita memiliki data
        News::create([
            'title' => 'Warta Terbaru MWC NU Wangon',
            'slug' => 'warta-terbaru-mwc-nu-wangon',
            'kategori' => 'muslimat',
            'content' => 'Isi berita warta terbaru',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Periksa halaman /berita
        $beritaPage = $this->get(route('berita.index'));
        $beritaPage->assertStatus(200);
        $beritaPage->assertSee($customOffice);
        $beritaPage->assertSee($customWa);
        $beritaPage->assertSee($customEmail);
        $beritaPage->assertSee('https://wa.me/6281399001122', false);

        // Periksa halaman /anggota/daftar
        $daftarPage = $this->get(route('anggota.daftar'));
        $daftarPage->assertStatus(200);
        $daftarPage->assertSee($customOffice);
        $daftarPage->assertSee($customWa);
        $daftarPage->assertSee($customEmail);

        // Periksa halaman /anggota/cek-status
        $cekStatusPage = $this->get(route('anggota.cek-status'));
        $cekStatusPage->assertStatus(200);
        $cekStatusPage->assertSee($customOffice);
        $cekStatusPage->assertSee($customWa);
        $cekStatusPage->assertSee($customEmail);
    }

    public function test_cache_invalidation_updates_public_pages_immediately_after_admin_saves(): void
    {
        $admin = $this->createAdmin();

        // Panggilan awal (menyimpan ke cache)
        $firstCall = $this->get(route('home'));
        $firstCall->assertStatus(200);

        // Admin memperbarui alamat
        $newAlamat = 'Jl. Siliwangi No. 101, Kompleks Pesantren Wangon';
        $this->actingAs($admin)->put(route('admin.pengaturan.sekretariat.update'), [
            'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
            'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
            'alamat' => $newAlamat,
        ]);

        // Panggilan kedua harus membaca nilai terbaru seketika tanpa stale cache
        $secondCall = $this->get(route('home'));
        $secondCall->assertStatus(200);
        $secondCall->assertSee($newAlamat);

        // Footer pada halaman lain juga harus membaca nilai terbaru seketika
        $daftarCall = $this->get(route('anggota.daftar'));
        $daftarCall->assertStatus(200);
        $daftarCall->assertSee($newAlamat);
    }

    public function test_multiple_view_renders_share_single_pengaturan_instance_without_redundant_queries(): void
    {
        // Pastikan tabel pengaturan memiliki record
        Pengaturan::ambil();

        // Hitung query saat rendering halaman beranda (yang merender view welcome + layout app + footer)
        DB::enableQueryLog();

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Hitung berapa kali tabel 'pengaturan' di-query selama satu request
        $pengaturanQueries = array_filter($queries, function ($q) {
            return str_contains(strtolower($q['query']), 'from `pengaturan`') ||
                   str_contains(strtolower($q['query']), 'from "pengaturan"');
        });

        // Dengan request-scoped memoization & cache, query pengaturan maksimal 1 kali
        $this->assertLessThanOrEqual(1, count($pengaturanQueries), 'Query ke tabel pengaturan tidak boleh berulang dalam satu request.');
    }
}
