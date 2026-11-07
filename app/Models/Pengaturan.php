<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Pengaturan extends Model
{
    public const CACHE_KEY = 'pengaturan_utama_instansi';

    protected $table = 'pengaturan';

    protected $fillable = [
        'tahun_berdiri',
        'label_section',
        'nama_kantor',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'telepon',
        'whatsapp',
        'telepon_alt',
        'email',
        'google_maps_url',
        'petunjuk_arah_url',
        'latitude',
        'longitude',
        'jam_operasional_hari',
        'jam_buka',
        'jam_tutup',
        'jam_operasional_status',
        'jam_operasional_keterangan',
    ];

    protected $casts = [
        'tahun_berdiri' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            self::lupakanCache();
        });

        static::deleted(function () {
            self::lupakanCache();
        });
    }

    /**
     * Bersihkan cache memori request dan persistent cache pengaturan.
     */
    public static function lupakanCache(): void
    {
        try {
            if (app()->bound('pengaturan_utama')) {
                app()->forgetInstance('pengaturan_utama');
            }
            Cache::forget(self::CACHE_KEY);
        } catch (\Throwable) {
            // Abaikan jika cache store belum tersedia
        }
    }

    /**
     * Ambil instance pengaturan utama dengan optimasi request-scope dan cache.
     */
    public static function ambil(): self
    {
        try {
            if (! Schema::hasTable('pengaturan')) {
                return self::defaultInstance();
            }

            if (app()->bound('pengaturan_utama')) {
                return app()->make('pengaturan_utama');
            }

            return Cache::remember(self::CACHE_KEY, 86400, function () {
                return self::firstOrCreate([], [
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
        } catch (\Throwable) {
            return self::defaultInstance();
        }
    }

    /**
     * Fallback aman jika database belum termigrasi.
     */
    public static function defaultInstance(): self
    {
        return new self([
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
    }

    /**
     * Normalisasi nomor telepon / WhatsApp menjadi format internasional (misal 628xxxxxxxxxx).
     */
    public static function normalizeWhatsappNumber(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        // Ambil hanya digit angka
        $digits = preg_replace('/[^0-9]/', '', $input);
        if (empty($digits)) {
            return null;
        }

        // Jika diawali 0, ganti dengan 62
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        // Validasi panjang minimum nomor telepon yang wajar (minimal 9 digit)
        if (strlen($digits) < 9) {
            return null;
        }

        return $digits;
    }

    /**
     * Tautan WhatsApp resmi untuk tombol "Hubungi Sekretariat".
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $number = $this->whatsapp ?: $this->telepon;
        $digits = self::normalizeWhatsappNumber($number);

        return $digits ? "https://wa.me/{$digits}" : null;
    }

    /**
     * Tautan Maps untuk tombol "Buka Petunjuk Arah Maps".
     */
    public function getMapsUrlAttribute(): ?string
    {
        if (! empty($this->google_maps_url)) {
            return $this->google_maps_url;
        }
        if (! empty($this->petunjuk_arah_url)) {
            return $this->petunjuk_arah_url;
        }
        if (! empty($this->latitude) && ! empty($this->longitude)) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
        }

        return null;
    }

    /**
     * Format string alamat lengkap gabungan untuk halaman publik.
     */
    public function getAlamatLengkapFormattedAttribute(): string
    {
        $alamatUtama = trim((string) $this->alamat);

        if (! empty($alamatUtama)) {
            // Jika admin sudah menuliskan alamat lengkap berisi nama kabupaten / provinsi
            if (str_contains(strtolower($alamatUtama), 'banyumas') || str_contains(strtolower($alamatUtama), 'jawa tengah')) {
                return rtrim($alamatUtama, '.').'.';
            }

            $components = array_filter([
                $alamatUtama,
                $this->desa ? (str_starts_with(strtolower($this->desa), 'desa') || str_starts_with(strtolower($this->desa), 'kel.') ? $this->desa : 'Desa '.$this->desa) : null,
                $this->kecamatan ? (str_starts_with(strtolower($this->kecamatan), 'kec.') ? $this->kecamatan : 'Kecamatan '.$this->kecamatan) : null,
                $this->kabupaten ? (str_starts_with(strtolower($this->kabupaten), 'kab.') ? $this->kabupaten : 'Kabupaten '.$this->kabupaten) : null,
                $this->provinsi,
                $this->kode_pos,
            ]);

            return implode(', ', $components).'.';
        }

        return 'Jl. Raya Wangon - Ajibarang, Dusun Sindurjan, Desa Wangon, Kecamatan Wangon, Kabupaten Banyumas, Jawa Tengah 53176.';
    }

    /**
     * Format jam operasional.
     */
    public function getJamOperasionalFormattedAttribute(): string
    {
        if (! empty($this->jam_operasional_keterangan)) {
            return $this->jam_operasional_keterangan;
        }

        $hari = $this->jam_operasional_hari ?: 'Senin - Ahad';
        $buka = $this->jam_buka ?: '08.00';
        $tutup = $this->jam_tutup ?: '16.00';

        return "{$hari}, {$buka} - {$tutup}";
    }
}
