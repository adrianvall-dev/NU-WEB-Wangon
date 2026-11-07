<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped('pengaturan_utama', function () {
            return Cache::remember(Pengaturan::CACHE_KEY, 86400, function () {
                return Pengaturan::firstOrCreate([], [
                    'tahun_berdiri' => 1999,
                    'label_section' => 'PUSAT LAYANAN & SEKRETARIAT',
                    'nama_kantor' => 'Kantor MWC Nahdlatul Ulama Wangon',
                    'alamat' => 'Jl. Raya Wangon - Ajibarang, Dusun Sindurjan',
                    'desa' => 'Wangon',
                    'kecamatan' => 'Wangon',
                    'kabupaten' => 'Banyumas',
                    'provinsi' => 'Jawa Tengah',
                    'kode_pos' => '53176',
                    'telepon' => '081234567890',
                    'whatsapp' => '081234567890',
                    'email' => 'sekretariat@nuwangon.or.id',
                    'google_maps_url' => 'https://maps.google.com/?q=Wangon+Banyumas',
                    'jam_operasional_hari' => 'Senin - Ahad',
                    'jam_buka' => '08.00',
                    'jam_tutup' => '16.00',
                    'jam_operasional_status' => 'Setiap Hari Kerja',
                ]);
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.footer', 'layouts.app', 'welcome'], function ($view) {
            $view->with('pengaturan', Pengaturan::ambil());
        });
    }
}
