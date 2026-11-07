<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BerandaAgendaKegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_displays_agenda_dan_kegiatan_section_title(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('AGENDA DAN KEGIATAN');
        $response->assertDontSee("AGENDA &amp; KEGIATAN JAM'IYYAH", false);
        $response->assertDontSee("AGENDA & KEGIATAN JAM'IYYAH", false);
    }

    public function test_beranda_displays_clean_empty_state_without_dummy_data_when_no_upcoming_events(): void
    {
        // Pastikan tidak ada data kegiatan di DB
        $this->assertEquals(0, Kegiatan::count());

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Agenda Mendatang');
        $response->assertDontSee('id="countdown-card"', false);

        // Pastikan card dummy/palsu lama tidak muncul
        $response->assertDontSee('Pengajian Selapanan Ahad Pahing');
        $response->assertDontSee('Diklatsar Banser Satkoryon');
        $response->assertDontSee('Santunan Akbar Yatim Piatu');
    }

    public function test_beranda_displays_real_upcoming_kegiatan_and_spotlight_countdown(): void
    {
        $upcomingDate = now()->addDays(3)->toDateString();
        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Rutin Muslimat Sindang',
            'kategori' => 'muslimat',
            'description' => 'Kajian kitab bersama pimpinan ranting dan muslimat.',
            'event_date' => $upcomingDate,
            'start_time' => '09:00:00',
            'end_time' => '11:30:00',
            'location' => 'Gedung Serbaguna Desa Sindang',
            'status' => 'published',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Pengajian Rutin Muslimat Sindang');
        $response->assertSee('Gedung Serbaguna Desa Sindang');
        $response->assertSee('id="countdown-card"', false);
        $response->assertSee('data-start="', false);
        $response->assertSee('data-end="', false);
        $response->assertSee('cdDays');
        $response->assertSee('cdHours');
        $response->assertSee('cdMinutes');
        $response->assertSee('cdSeconds');
        $response->assertDontSee('Belum Ada Agenda Mendatang');
    }

    public function test_beranda_does_not_display_past_events_in_upcoming_section(): void
    {
        // Event yang sudah lampau (kemarin)
        $pastDate = now()->subDays(2)->toDateString();
        Kegiatan::create([
            'title' => 'Peringatan Hari Santri Lampau',
            'kategori' => 'muslimat',
            'description' => 'Kegiatan yang sudah selesai dilaksanakan.',
            'event_date' => $pastDate,
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'location' => 'Alun-alun Sindang',
            'status' => 'published',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Event lampau tidak boleh muncul sebagai agenda mendatang di Beranda
        $response->assertDontSee('Peringatan Hari Santri Lampau');
        $response->assertSee('Belum Ada Agenda Mendatang');
    }

    public function test_beranda_orders_upcoming_kegiatan_by_closest_date_for_spotlight(): void
    {
        // Event 7 hari ke depan
        Kegiatan::create([
            'title' => 'Musyawarah Kerja Cabang',
            'kategori' => 'ansor',
            'description' => 'Musker diadakan minggu depan.',
            'event_date' => now()->addDays(7)->toDateString(),
            'start_time' => '08:00:00',
            'location' => 'Aula Pertemuan',
            'status' => 'published',
        ]);

        // Event 1 hari ke depan (terdekat)
        Kegiatan::create([
            'title' => 'Majelis Dzikir Terdekat',
            'kategori' => 'fatayat',
            'description' => 'Majelis dzikir besok pagi.',
            'event_date' => now()->addDays(1)->toDateString(),
            'start_time' => '06:00:00',
            'location' => 'Masjid Jami',
            'status' => 'published',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Keduanya ada di grid
        $response->assertSee('Majelis Dzikir Terdekat');
        $response->assertSee('Musyawarah Kerja Cabang');

        // Spotlight countdown harus mengarah ke kegiatan terdekat
        $kegiatanTerdekat = $response->viewData('kegiatanTerdekat');
        $this->assertNotNull($kegiatanTerdekat);
        $this->assertEquals('Majelis Dzikir Terdekat', $kegiatanTerdekat->title);
    }
}
