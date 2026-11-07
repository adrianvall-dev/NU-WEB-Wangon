<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Organisasi
{
    public const ANSOR = 'ansor';

    public const FATAYAT = 'fatayat';

    public const MUSLIMAT = 'muslimat';

    public const IPNU = 'ipnu';

    public const IPPNU = 'ippnu';

    public const PAGAR_NUSA = 'pagar_nusa';

    /**
     * Master banom / organization list (slugs).
     *
     * @return array<string>
     */
    public static function all(): array
    {
        try {
            if (Schema::hasTable('organisasi')) {
                $dbSlugs = DB::table('organisasi')->pluck('slug')->toArray();
                if (! empty($dbSlugs)) {
                    return array_values(array_unique([
                        self::ANSOR,
                        self::FATAYAT,
                        self::MUSLIMAT,
                        self::IPNU,
                        self::IPPNU,
                        self::PAGAR_NUSA,
                        ...$dbSlugs,
                    ]));
                }
            }
        } catch (\Throwable $e) {
        }

        return [
            self::ANSOR,
            self::FATAYAT,
            self::MUSLIMAT,
            self::IPNU,
            self::IPPNU,
            self::PAGAR_NUSA,
        ];
    }

    /**
     * Master banom list including 'umum' for news and activities.
     *
     * @return array<string>
     */
    public static function allWithUmum(): array
    {
        return [
            ...self::all(),
            'umum',
        ];
    }

    /**
     * Mapping of slugs to display labels.
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $defaults = [
            self::ANSOR => 'Ansor',
            self::FATAYAT => 'Fatayat',
            self::MUSLIMAT => 'Muslimat',
            self::IPNU => 'IPNU',
            self::IPPNU => 'IPPNU',
            self::PAGAR_NUSA => 'Pagar Nusa',
        ];

        try {
            if (Schema::hasTable('organisasi')) {
                $dbItems = DB::table('organisasi')->pluck('singkatan', 'slug')->toArray();
                foreach ($dbItems as $slug => $singkatan) {
                    if (! isset($defaults[$slug]) && $singkatan) {
                        $defaults[$slug] = $singkatan;
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        return $defaults;
    }

    /**
     * Mapping with public/official badge labels.
     *
     * @return array<string, string>
     */
    public static function badgeLabels(): array
    {
        $defaults = [
            self::ANSOR => 'GP Ansor',
            self::FATAYAT => 'Fatayat NU',
            self::MUSLIMAT => 'Muslimat NU',
            self::IPNU => 'IPNU',
            self::IPPNU => 'IPPNU',
            self::PAGAR_NUSA => 'Pagar Nusa',
        ];

        try {
            if (Schema::hasTable('organisasi')) {
                $dbItems = DB::table('organisasi')->pluck('singkatan', 'slug')->toArray();
                foreach ($dbItems as $slug => $singkatan) {
                    if (! isset($defaults[$slug]) && $singkatan) {
                        $defaults[$slug] = $singkatan;
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        return $defaults;
    }

    /**
     * Mapping with public/official badge labels including 'umum'.
     *
     * @return array<string, string>
     */
    public static function badgeLabelsWithUmum(): array
    {
        return [
            ...self::badgeLabels(),
            'umum' => 'Semua Kalangan',
        ];
    }

    /**
     * Mapping for registration dropdown descriptions.
     *
     * @return array<string, string>
     */
    public static function registrationOptions(): array
    {
        $defaults = [
            self::ANSOR => 'Gerakan Pemuda Ansor (Pemuda NU)',
            self::FATAYAT => 'Fatayat NU (Pemudi NU)',
            self::MUSLIMAT => 'Muslimat NU (Kaum Ibu NU)',
            self::IPNU => 'Ikatan Pelajar Nahdlatul Ulama (IPNU)',
            self::IPPNU => 'Ikatan Pelajar Putri Nahdlatul Ulama (IPPNU)',
            self::PAGAR_NUSA => 'Pencak Silat NU Pagar Nusa',
        ];

        try {
            if (Schema::hasTable('organisasi')) {
                $dbItems = DB::table('organisasi')->get(['slug', 'nama', 'singkatan']);
                foreach ($dbItems as $item) {
                    if (! isset($defaults[$item->slug])) {
                        $defaults[$item->slug] = $item->nama.($item->singkatan ? " ({$item->singkatan})" : '');
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        return $defaults;
    }

    /**
     * Member ID / Card code prefixes (3 letters uppercase).
     *
     * @return array<string, string>
     */
    public static function prefixes(): array
    {
        return [
            self::ANSOR => 'ANS',
            self::FATAYAT => 'FAT',
            self::MUSLIMAT => 'MSL',
            self::IPNU => 'IPN',
            self::IPPNU => 'IPP',
            self::PAGAR_NUSA => 'PGN',
        ];
    }

    /**
     * Get label by slug.
     */
    public static function label(?string $slug): string
    {
        if (! $slug) {
            return 'Organisasi NU';
        }

        return self::labels()[$slug] ?? ucfirst(str_replace('_', ' ', $slug));
    }

    /**
     * Get badge label by slug.
     */
    public static function badgeLabel(?string $slug): string
    {
        if (! $slug) {
            return 'Umum';
        }

        if ($slug === 'umum') {
            return 'Semua Kalangan';
        }

        return self::badgeLabels()[$slug] ?? ucfirst(str_replace('_', ' ', $slug));
    }

    /**
     * Get official full formal organization name.
     */
    public static function fullName(?string $slug): string
    {
        return match ($slug) {
            self::ANSOR => 'Gerakan Pemuda Ansor',
            self::FATAYAT => 'Fatayat Nahdlatul Ulama',
            self::MUSLIMAT => 'Muslimat Nahdlatul Ulama',
            self::IPNU => 'Ikatan Pelajar Nahdlatul Ulama',
            self::IPPNU => 'Ikatan Pelajar Putri Nahdlatul Ulama',
            self::PAGAR_NUSA => 'Pencak Silat Nahdlatul Ulama Pagar Nusa',
            default => 'Organisasi NU Wangon',
        };
    }

    /**
     * Get prefix for member ID.
     */
    public static function prefix(?string $slug): string
    {
        return self::prefixes()[$slug] ?? strtoupper(substr(str_replace('_', '', $slug ?? 'NU'), 0, 3));
    }

    /**
     * Validation rule for banom.
     */
    public static function validationRule(): string
    {
        return 'in:'.implode(',', self::all());
    }

    /**
     * Validation rule for news & kegiatan including 'umum'.
     */
    public static function validationRuleWithUmum(): string
    {
        return 'in:'.implode(',', self::allWithUmum());
    }
}
