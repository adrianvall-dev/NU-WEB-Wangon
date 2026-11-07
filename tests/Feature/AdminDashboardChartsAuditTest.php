<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardChartsAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_dashboard_renders_with_real_chart_periods_and_accurate_counters(): void
    {
        $admin = $this->createAdmin();

        $now = Carbon::now();

        // 1. Create News
        News::create([
            'title' => 'Warta Berita Satu',
            'slug' => 'warta-berita-satu',
            'kategori' => 'muslimat',
            'content' => 'Isi berita warta satu',
            'status' => 'published',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        News::create([
            'title' => 'Warta Berita Dua (Draft)',
            'slug' => 'warta-berita-dua',
            'kategori' => 'muslimat',
            'content' => 'Isi draft berita dua',
            'status' => 'draft',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Create Kegiatan
        Kegiatan::create([
            'title' => 'Kegiatan Pengajian Rutin',
            'kategori' => 'muslimat',
            'description' => 'Deskripsi pengajian rutin',
            'event_date' => $now->format('Y-m-d'),
            'start_time' => '08:00:00',
            'location' => 'Masjid Wangon',
            'status' => 'published',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalBerita', 2);
        $response->assertViewHas('totalKegiatan', 1);
        $response->assertViewHas('beritaPublished', 1);
        $response->assertViewHas('kegiatanPublished', 1);

        // Verify activityChart payload structure
        $response->assertViewHas('activityChart', function (array $chart) use ($now) {
            $this->assertArrayHasKey('12', $chart);
            $this->assertArrayHasKey('6', $chart);
            $this->assertArrayHasKey('all', $chart);

            // 12B checks
            $currentMonthIdx = (int) $now->format('n') - 1;
            $this->assertCount(12, $chart['12']['labels']);
            $this->assertEquals(2, $chart['12']['berita'][$currentMonthIdx]);
            $this->assertEquals(1, $chart['12']['kegiatan'][$currentMonthIdx]);

            // 6B checks
            $this->assertCount(6, $chart['6']['labels']);
            $this->assertEquals(2, end($chart['6']['berita']));
            $this->assertEquals(1, end($chart['6']['kegiatan']));

            // All-time checks
            $this->assertNotEmpty($chart['all']['labels']);
            $this->assertContains((string) $now->year, $chart['all']['labels']);

            return true;
        });

        // Ensure canvas element and filter toggle attributes are present
        $response->assertSee('id="nuRefActivityChart"', false);
        $response->assertSee('data-period="12"', false);
        $response->assertSee('data-period="6"', false);
        $response->assertSee('data-period="all"', false);
    }

    public function test_dashboard_handles_empty_database_gracefully(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalBerita', 0);
        $response->assertViewHas('totalKegiatan', 0);
        $response->assertViewHas('beritaPublished', 0);
        $response->assertViewHas('kegiatanPublished', 0);

        $response->assertViewHas('activityChart', function (array $chart) {
            $this->assertCount(12, $chart['12']['labels']);
            $this->assertEquals(array_fill(0, 12, 0), $chart['12']['berita']);
            $this->assertEquals(array_fill(0, 12, 0), $chart['12']['kegiatan']);

            $this->assertCount(6, $chart['6']['labels']);
            $this->assertEquals(array_fill(0, 6, 0), $chart['6']['berita']);
            $this->assertEquals(array_fill(0, 6, 0), $chart['6']['kegiatan']);

            return true;
        });
    }

    public function test_kegiatan_terbaru_orders_chronologically_by_event_date(): void
    {
        $admin = $this->createAdmin();

        $eventLater = Kegiatan::create([
            'title' => 'Agenda Bulan Depan',
            'kategori' => 'muslimat',
            'description' => 'Deskripsi agenda',
            'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'location' => 'Kantor MWC',
            'status' => 'published',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        $eventEarlier = Kegiatan::create([
            'title' => 'Agenda Besok',
            'kategori' => 'muslimat',
            'description' => 'Deskripsi agenda besok',
            'event_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'location' => 'Kantor MWC',
            'status' => 'published',
            'created_at' => Carbon::now(), // created later, but event is earlier
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('kegiatanTerbaru', function ($feed) use ($eventLater) {
            // First item in feed should be the latest upcoming event_date
            return $feed->first()->id === $eventLater->id;
        });
    }

    public function test_kegiatan_index_computes_real_stats_and_7day_trend(): void
    {
        $admin = $this->createAdmin();

        for ($i = 0; $i < 12; $i++) {
            Kegiatan::create([
                'title' => "Agenda Batch {$i}",
                'kategori' => 'muslimat',
                'description' => 'Deskripsi',
                'event_date' => Carbon::now()->subDays($i % 5)->format('Y-m-d'),
                'start_time' => '08:00:00',
                'location' => 'Sindang',
                'status' => $i < 8 ? 'published' : 'draft',
                'created_at' => Carbon::now()->subDays($i % 5),
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.kegiatan.index'));

        $response->assertOk();
        $response->assertViewHas('totalKegiatan', 12);
        $response->assertViewHas('publishedCount', 8);
        $response->assertViewHas('draftCount', 4);

        $response->assertViewHas('chartLabels', function ($labels) {
            return count($labels) === 7;
        });

        $response->assertViewHas('chartData', function ($data) {
            return count($data) === 7 && array_sum($data) === 12;
        });

        $response->assertSee('id="kegiatanChart"', false);
    }

    public function test_anggota_index_computes_real_database_stats_beyond_pagination(): void
    {
        $admin = $this->createAdmin();

        // Create 15 members (exceeds pagination limit of 10)
        for ($i = 0; $i < 15; $i++) {
            Anggota::create([
                'nama' => "Anggota {$i}",
                'organisasi' => $i < 9 ? 'fatayat' : 'muslimat',
                'status' => $i < 12 ? 'aktif' : 'nonaktif',
                'nomor_anggota' => "ANG-{$i}",
                'status_pendaftaran' => 'approved',
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.anggota.index'));

        $response->assertOk();
        $response->assertViewHas('totalAnggota', 15);
        $response->assertViewHas('aktifCount', 12);
        $response->assertViewHas('banomPemudaCount', 9); // Fatayat count
        $response->assertViewHas('muslimatBanomCount', 15); // Fatayat + Muslimat count

        // Response should render 15 for total anggota, not 10
        $response->assertSee('15');
        $response->assertSee('12');
    }
}
