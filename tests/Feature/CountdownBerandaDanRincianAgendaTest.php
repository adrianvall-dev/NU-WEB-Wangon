<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountdownBerandaDanRincianAgendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_countdown_beranda_dan_rincian_agenda_konsisten_menggunakan_target_waktu_yang_sama(): void
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $eventDate = now($tz)->addDays(4)->toDateString();

        $kegiatan = Kegiatan::create([
            'title' => 'Konferensi MWC NU Wangon Terdekat',
            'kategori' => 'ansor',
            'description' => 'Pembahasan program kerja dan penguatan kelembagaan tingkat kecamatan.',
            'event_date' => $eventDate,
            'start_time' => '08:30:00',
            'end_time' => '12:00:00',
            'location' => 'Gedung MWC NU Wangon',
            'status' => 'published',
        ]);

        // 1. Periksa halaman Beranda
        $resBeranda = $this->get('/');
        $resBeranda->assertStatus(200);
        $resBeranda->assertSee('id="countdown-card"', false);
        $resBeranda->assertSee('Konferensi MWC NU Wangon Terdekat');
        $resBeranda->assertSee('cdDays');
        $resBeranda->assertSee('cdHours');
        $resBeranda->assertSee('cdMinutes');
        $resBeranda->assertSee('cdSeconds');

        // 2. Periksa halaman Rincian Agenda
        $resDetail = $this->get(route('kegiatan.show', $kegiatan->id));
        $resDetail->assertStatus(200);
        $resDetail->assertSee('id="countdown-card"', false);
        $resDetail->assertSee('Konferensi MWC NU Wangon Terdekat');
        $resDetail->assertSee('id="days"', false);
        $resDetail->assertSee('id="hours"', false);
        $resDetail->assertSee('id="minutes"', false);
        $resDetail->assertSee('id="seconds"', false);
        $resDetail->assertSee('initDetailCountdown');

        // Pastikan tidak ada syntax error atau duplicate brace
        $resDetail->assertDontSee('const startDate = new Date("', false);

        // 3. Ambil data-start dan data-end dari Beranda
        preg_match('/id="countdown-card"[^>]*data-start="(\d+)"[^>]*data-end="(\d+)"/', $resBeranda->getContent(), $berandaMatches);
        $this->assertNotEmpty($berandaMatches, 'Atribut data-start dan data-end harus ada di countdown Beranda');
        $berandaStart = $berandaMatches[1];
        $berandaEnd = $berandaMatches[2];

        // 4. Ambil data-start dan data-end dari Rincian Agenda
        preg_match('/id="countdown-card"[^>]*data-start="(\d+)"[^>]*data-end="(\d+)"/', $resDetail->getContent(), $detailMatches);
        $this->assertNotEmpty($detailMatches, 'Atribut data-start dan data-end harus ada di countdown Rincian Agenda');
        $detailStart = $detailMatches[1];
        $detailEnd = $detailMatches[2];

        // 5. Pastikan TARGET WAKTU KEDUANYA 100% SAMA DAN KONSISTEN
        $this->assertEquals($berandaStart, $detailStart, 'Timestamp mulai acara di Beranda dan Rincian Agenda harus persis sama');
        $this->assertEquals($berandaEnd, $detailEnd, 'Timestamp selesai acara di Beranda dan Rincian Agenda harus persis sama');
    }

    public function test_rincian_agenda_menampilkan_status_sedang_berlangsung_saat_waktu_acara_tiba(): void
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $today = now($tz)->toDateString();
        $startTime = now($tz)->subHour()->format('H:i:s');
        $endTime = now($tz)->addHour()->format('H:i:s');

        $kegiatan = Kegiatan::create([
            'title' => 'Bakti Sosial Sedang Berjalan',
            'kategori' => 'fatayat',
            'description' => 'Penyaluran bantuan sosial bagi masyarakat yang membutuhkan.',
            'event_date' => $today,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => 'Kantor Desa Sindang',
            'status' => 'published',
        ]);

        $response = $this->get(route('kegiatan.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Sedang Berlangsung');
        $response->assertSee('Acara Sedang Berlangsung');
    }

    public function test_rincian_agenda_menampilkan_status_selesai_saat_kegiatan_telah_lewat(): void
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $pastDate = now($tz)->subDays(3)->toDateString();

        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Akbar Selesai',
            'kategori' => 'muslimat',
            'description' => 'Pengajian telah dilaksanakan minggu lalu.',
            'event_date' => $pastDate,
            'start_time' => '08:00:00',
            'end_time' => '11:00:00',
            'location' => 'Masjid Jami',
            'status' => 'published',
        ]);

        $response = $this->get(route('kegiatan.show', $kegiatan->id));

        $response->assertStatus(200);
        $response->assertSee('Selesai');
        // Countdown timer tidak ditampilkan untuk acara yang sudah selesai
        $response->assertDontSee('id="countdown-card"', false);
    }
}
