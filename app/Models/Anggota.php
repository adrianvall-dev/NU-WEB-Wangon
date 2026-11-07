<?php

namespace App\Models;

use App\Support\Organisasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nama',
        'nik',
        'organisasi',
        'jabatan',
        'alamat',
        'no_hp',
        'foto',
        'status',
        'status_pendaftaran',
        'nomor_anggota',
        'posisi_pendaftaran',
        'kode_pendaftaran',
        'kode_verifikasi',
        'alasan_penolakan',
    ];

    /**
     * Membuat kode pendaftaran unik, dipakai saat pendaftar submit form publik.
     */
    public static function buatKodePendaftaran(): string
    {
        do {
            $kode = 'REG-'.strtoupper(Str::random(8));
        } while (self::where('kode_pendaftaran', $kode)->exists());

        return $kode;
    }

    /**
     * Membuat kode verifikasi unik, dipakai setelah pendaftaran disetujui admin.
     */
    public static function buatKodeVerifikasi(): string
    {
        do {
            $kode = 'VRF-'.strtoupper(Str::random(10));
        } while (self::where('kode_verifikasi', $kode)->exists());

        return $kode;
    }

    /**
     * Membuat nomor anggota unik, dipakai setelah pendaftaran disetujui admin.
     * Format: NU-<organisasi 3 huruf>-<tahun>-<urutan 4 digit>
     */
    public static function buatNomorAnggota(string $organisasi): string
    {
        $prefix = Organisasi::prefix($organisasi);
        $tahun = date('Y');

        $urutan = self::whereNotNull('nomor_anggota')
            ->where('nomor_anggota', 'like', "NU-{$prefix}-{$tahun}-%")
            ->count() + 1;

        do {
            $nomor = sprintf('NU-%s-%s-%04d', $prefix, $tahun, $urutan);
            $urutan++;
        } while (self::where('nomor_anggota', $nomor)->exists());

        return $nomor;
    }

    /**
     * NIK ter-mask untuk ditampilkan di kartu/preview (contoh: 351************).
     */
    public function getNikMaskedAttribute(): ?string
    {
        if (! $this->nik) {
            return null;
        }

        $panjang = strlen($this->nik);
        if ($panjang <= 3) {
            return str_repeat('*', $panjang);
        }

        return substr($this->nik, 0, 3).str_repeat('*', $panjang - 3);
    }

    /**
     * No HP ter-mask untuk ditampilkan di kartu/preview (contoh: 0812******90).
     */
    public function getNoHpMaskedAttribute(): ?string
    {
        if (! $this->no_hp) {
            return null;
        }

        $panjang = strlen($this->no_hp);
        if ($panjang <= 6) {
            return str_repeat('*', $panjang);
        }

        $depan = substr($this->no_hp, 0, 4);
        $belakang = substr($this->no_hp, -2);

        return $depan.str_repeat('*', $panjang - 6).$belakang;
    }

    /**
     * Relasi ke model Organisasi berdasarkan slug.
     */
    public function organisasiModel(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'organisasi', 'slug');
    }

    /**
     * Cek apakah anggota adalah ketua organisasi.
     */
    public function isKetua(): bool
    {
        if (strtolower($this->jabatan ?? '') === 'ketua') {
            return true;
        }

        return $this->organisasiModel && $this->organisasiModel->ketua_id === $this->id;
    }

    /**
     * Cek apakah anggota adalah wakil ketua organisasi.
     */
    public function isWakilKetua(): bool
    {
        if (str_contains(strtolower($this->jabatan ?? ''), 'wakil')) {
            return true;
        }

        return $this->organisasiModel && $this->organisasiModel->wakil_ketua_id === $this->id;
    }

    /**
     * Label posisi pendaftaran (Ketua / Wakil Ketua / Anggota).
     */
    public function getPosisiPendaftaranLabelAttribute(): string
    {
        return match ($this->posisi_pendaftaran) {
            'ketua' => 'Ketua',
            'wakil_ketua' => 'Wakil Ketua',
            default => 'Anggota',
        };
    }
}
