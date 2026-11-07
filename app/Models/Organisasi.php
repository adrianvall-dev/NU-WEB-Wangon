<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Organisasi extends Model
{
    use HasFactory;

    protected $table = 'organisasi';

    protected $fillable = [
        'nama',
        'slug',
        'singkatan',
        'deskripsi',
        'logo',
        'visi',
        'misi',
        'ketua_id',
        'wakil_ketua_id',
    ];

    protected $casts = [
        'misi' => 'array',
    ];

    /**
     * Relasi ke ketua (Anggota).
     */
    public function ketua(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'ketua_id');
    }

    /**
     * Relasi ke wakil ketua (Anggota).
     */
    public function wakilKetua(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'wakil_ketua_id');
    }

    /**
     * Seluruh anggota yang tergabung dalam organisasi ini.
     */
    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class, 'organisasi', 'slug');
    }

    /**
     * Anggota aktif dalam organisasi ini.
     */
    public function activeAnggotas(): HasMany
    {
        return $this->anggotas()->where('status', 'aktif');
    }

    /**
     * Cek apakah organisasi sudah memiliki ketua aktif.
     */
    public function hasActiveKetua(): bool
    {
        return $this->getActiveKetua() !== null;
    }

    /**
     * Dapatkan model ketua yang aktif.
     */
    public function getActiveKetua(): ?Anggota
    {
        if ($this->ketua_id && $this->ketua && $this->ketua->status === 'aktif') {
            return $this->ketua;
        }

        return $this->activeAnggotas()
            ->where(function ($q) {
                $q->where('jabatan', 'ketua')
                    ->orWhere('jabatan', 'Ketua')
                    ->orWhere('jabatan', 'like', 'Ketua %');
            })
            ->first();
    }

    /**
     * Cek apakah organisasi sudah memiliki wakil ketua aktif.
     */
    public function hasActiveWakilKetua(): bool
    {
        return $this->getActiveWakilKetua() !== null;
    }

    /**
     * Dapatkan model wakil ketua yang aktif.
     */
    public function getActiveWakilKetua(): ?Anggota
    {
        if ($this->wakil_ketua_id && $this->wakilKetua && $this->wakilKetua->status === 'aktif') {
            return $this->wakilKetua;
        }

        return $this->activeAnggotas()
            ->where(function ($q) {
                $q->where('jabatan', 'like', '%wakil%');
            })
            ->first();
    }

    /**
     * Dapatkan daftar butir misi sebagai array string yang bersih.
     *
     * @return array<string>
     */
    public function getMisiList(): array
    {
        if (is_array($this->misi)) {
            return array_values(array_filter(array_map('trim', $this->misi)));
        }

        if (is_string($this->misi)) {
            $lines = preg_split('/\r\n|\r|\n/', $this->misi);

            return array_values(array_filter(array_map('trim', (array) $lines)));
        }

        return [];
    }

    /**
     * Dapatkan URL logo organisasi jika tersedia.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/'.$this->logo);
        }

        return null;
    }

    /**
     * Dapatkan inisial 2-4 huruf untuk avatar organisasi dari singkatan atau nama.
     */
    public function getInisialAttribute(): string
    {
        $source = trim((string) ($this->singkatan ?: $this->nama));
        if ($source === '') {
            return 'NU';
        }

        // Jika singkatan ringkas (2-4 karakter tanpa spasi seperti 'IPNU', 'IPP', 'PR', 'PAC', 'ANS')
        if (mb_strlen($source) <= 4 && ! str_contains($source, ' ')) {
            return strtoupper($source);
        }

        // Ambil huruf pertama tiap kata jika terdiri dari beberapa kata (misal 'Fatayat NU' -> 'FN', 'Pagar Nusa' -> 'PN', 'GP Ansor' -> 'GA')
        $words = preg_split('/\s+/', $source);
        if (is_array($words) && count($words) >= 2) {
            $initials = '';
            foreach (array_slice($words, 0, 3) as $w) {
                if ($w !== '') {
                    $initials .= mb_substr($w, 0, 1);
                }
            }

            if ($initials !== '') {
                return strtoupper(mb_substr($initials, 0, 3));
            }
        }

        if (is_array($words) && count($words) === 1) {
            return strtoupper(mb_substr($source, 0, min(3, mb_strlen($source))));
        }

        return strtoupper(mb_substr($source, 0, 2));
    }
}
