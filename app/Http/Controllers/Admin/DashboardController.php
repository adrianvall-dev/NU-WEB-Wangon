<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = (int) date('Y');

        // 1. Total & Status Counter
        $totalBerita = News::count();
        $totalKegiatan = Kegiatan::count();
        $beritaPublished = News::where('status', 'published')->count();
        $kegiatanPublished = Kegiatan::where('status', 'published')->count();

        // 2. Data Terbaru
        $beritaTerbaru = News::orderBy('created_at', 'desc')->take(5)->get();
        // Agenda kegiatan diurutkan kronologis tanggal pelaksanaan
        $kegiatanTerbaru = Kegiatan::orderBy('event_date', 'desc')->orderBy('start_time', 'desc')->take(5)->get();

        // 3. Query Realtime Grafik Aktivitas (12B, 6B, Semua)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        // --- PERIOD 12B (12 Bulan Tahun Berjalan: Jan - Des) ---
        $newsMonthExpr = $isSqlite
            ? "cast(strftime('%m', created_at) as integer) as month"
            : 'MONTH(created_at) as month';
        $kegiatanMonthExpr = $isSqlite
            ? "cast(strftime('%m', COALESCE(event_date, created_at)) as integer) as month"
            : 'MONTH(COALESCE(event_date, created_at)) as month';

        $rawBerita12B = News::selectRaw("{$newsMonthExpr}, COUNT(*) as count")
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $rawKegiatan12B = Kegiatan::selectRaw("{$kegiatanMonthExpr}, COUNT(*) as count")
            ->where(function ($q) use ($currentYear, $isSqlite) {
                if ($isSqlite) {
                    $q->whereRaw("cast(strftime('%Y', COALESCE(event_date, created_at)) as integer) = ?", [$currentYear]);
                } else {
                    $q->whereRaw('YEAR(COALESCE(event_date, created_at)) = ?', [$currentYear]);
                }
            })
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $labels12B = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyBerita12 = [];
        $monthlyKegiatan12 = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyBerita12[] = (int) ($rawBerita12B[$m] ?? 0);
            $monthlyKegiatan12[] = (int) ($rawKegiatan12B[$m] ?? 0);
        }

        // --- PERIOD 6B (Rolling 6 Bulan Terakhir) ---
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $newsYmExpr = $isSqlite
            ? "strftime('%Y-%m', created_at) as ym"
            : "DATE_FORMAT(created_at, '%Y-%m') as ym";
        $kegiatanYmExpr = $isSqlite
            ? "strftime('%Y-%m', COALESCE(event_date, created_at)) as ym"
            : "DATE_FORMAT(COALESCE(event_date, created_at), '%Y-%m') as ym";

        $rawBerita6B = News::selectRaw("{$newsYmExpr}, COUNT(*) as count")
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('ym')
            ->pluck('count', 'ym');

        $rawKegiatan6B = Kegiatan::selectRaw("{$kegiatanYmExpr}, COUNT(*) as count")
            ->where(function ($q) use ($sixMonthsAgo, $isSqlite) {
                if ($isSqlite) {
                    $q->whereRaw('COALESCE(event_date, created_at) >= ?', [$sixMonthsAgo->format('Y-m-d')]);
                } else {
                    $q->whereRaw('COALESCE(event_date, created_at) >= ?', [$sixMonthsAgo->format('Y-m-d H:i:s')]);
                }
            })
            ->groupBy('ym')
            ->pluck('count', 'ym');

        $labels6B = [];
        $berita6B = [];
        $kegiatan6B = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $ym = $dt->format('Y-m');
            $labels6B[] = $dt->translatedFormat('M');
            $berita6B[] = (int) ($rawBerita6B->get($ym, 0));
            $kegiatan6B[] = (int) ($rawKegiatan6B->get($ym, 0));
        }

        // --- PERIOD SEMUA (Tren Tahunan Riil) ---
        $newsYearExpr = $isSqlite
            ? "cast(strftime('%Y', created_at) as integer) as year"
            : 'YEAR(created_at) as year';
        $kegiatanYearExpr = $isSqlite
            ? "cast(strftime('%Y', COALESCE(event_date, created_at)) as integer) as year"
            : 'YEAR(COALESCE(event_date, created_at)) as year';

        $rawBeritaAll = News::selectRaw("{$newsYearExpr}, COUNT(*) as count")
            ->groupBy('year')
            ->pluck('count', 'year');

        $rawKegiatanAll = Kegiatan::selectRaw("{$kegiatanYearExpr}, COUNT(*) as count")
            ->groupBy('year')
            ->pluck('count', 'year');

        $minYearNews = News::min(DB::raw($isSqlite ? "cast(strftime('%Y', created_at) as integer)" : 'YEAR(created_at)'));
        $minYearKegiatan = Kegiatan::min(DB::raw($isSqlite ? "cast(strftime('%Y', COALESCE(event_date, created_at)) as integer)" : 'YEAR(COALESCE(event_date, created_at))'));

        $startYear = min((int) ($minYearNews ?: $currentYear), (int) ($minYearKegiatan ?: $currentYear), $currentYear - 2);

        $labelsAll = [];
        $beritaAll = [];
        $kegiatanAll = [];
        for ($yr = $startYear; $yr <= $currentYear; $yr++) {
            $labelsAll[] = (string) $yr;
            $beritaAll[] = (int) ($rawBeritaAll->get($yr, 0));
            $kegiatanAll[] = (int) ($rawKegiatanAll->get($yr, 0));
        }

        $activityChart = [
            '12' => [
                'labels' => $labels12B,
                'berita' => $monthlyBerita12,
                'kegiatan' => $monthlyKegiatan12,
            ],
            '6' => [
                'labels' => $labels6B,
                'berita' => $berita6B,
                'kegiatan' => $kegiatan6B,
            ],
            'all' => [
                'labels' => $labelsAll,
                'berita' => $beritaAll,
                'kegiatan' => $kegiatanAll,
            ],
        ];

        return view('admin.dashboard', [
            'totalBerita' => $totalBerita,
            'totalKegiatan' => $totalKegiatan,
            'beritaPublished' => $beritaPublished,
            'kegiatanPublished' => $kegiatanPublished,
            'beritaTerbaru' => $beritaTerbaru,
            'kegiatanTerbaru' => $kegiatanTerbaru,
            'monthlyBerita' => $monthlyBerita12,
            'monthlyKegiatan' => $monthlyKegiatan12,
            'activityChart' => $activityChart,
            'historyKegiatan' => Kegiatan::selesai()->orderBy('event_date', 'desc')->take(5)->get(),
        ]);
    }
}
