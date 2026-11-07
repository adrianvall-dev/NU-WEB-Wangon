<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'title',
        'kategori',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'latitude',
        'longitude',
        'image',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Relasi ke foto-foto dokumentasi galeri.
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(FotoKegiatan::class, 'kegiatan_id')->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Periksa apakah kegiatan sudah selesai dilaksanakan.
     */
    public function isSelesai(): bool
    {
        if ($this->status === 'draft') {
            return false;
        }

        if ($this->status === 'selesai') {
            return true;
        }

        if (! $this->event_date) {
            return false;
        }

        $dateStr = $this->event_date instanceof Carbon ? $this->event_date->format('Y-m-d') : (string) $this->event_date;
        $timeStr = $this->end_time ?: ($this->start_time ?: '23:59:59');

        return Carbon::parse($dateStr.' '.$timeStr)->isPast();
    }

    /**
     * Scope untuk kegiatan yang sudah selesai (History Kegiatan / Galeri).
     */
    public function scopeSelesai(Builder $query): Builder
    {
        $today = now()->format('Y-m-d');
        $timeNow = now()->format('H:i:s');

        return $query->where(function (Builder $outer) use ($today, $timeNow) {
            $outer->where('status', 'selesai')
                ->orWhere(function (Builder $published) use ($today, $timeNow) {
                    $published->where('status', 'published')
                        ->where(function (Builder $q) use ($today, $timeNow) {
                            $q->where('event_date', '<', $today)
                                ->orWhere(function (Builder $sameDay) use ($today, $timeNow) {
                                    $sameDay->where('event_date', '=', $today)
                                        ->where(function (Builder $t) use ($timeNow) {
                                            $t->where(function (Builder $hasEnd) use ($timeNow) {
                                                $hasEnd->whereNotNull('end_time')
                                                    ->where('end_time', '<=', $timeNow);
                                            })->orWhere(function (Builder $noEnd) use ($timeNow) {
                                                $noEnd->whereNull('end_time')
                                                    ->where('start_time', '<=', $timeNow);
                                            });
                                        });
                                });
                        });
                });
        });
    }

    /**
     * Scope untuk kegiatan yang masih aktif / akan datang.
     */
    public function scopeMendatang(Builder $query): Builder
    {
        $today = now()->format('Y-m-d');
        $timeNow = now()->format('H:i:s');

        return $query->where('status', 'published')
            ->where(function (Builder $q) use ($today, $timeNow) {
                $q->where('event_date', '>', $today)
                    ->orWhere(function (Builder $sameDay) use ($today, $timeNow) {
                        $sameDay->where('event_date', '=', $today)
                            ->where(function (Builder $t) use ($timeNow) {
                                $t->where(function (Builder $hasEnd) use ($timeNow) {
                                    $hasEnd->whereNotNull('end_time')
                                        ->where('end_time', '>', $timeNow);
                                })->orWhere(function (Builder $noEnd) use ($timeNow) {
                                    $noEnd->whereNull('end_time')
                                        ->where('start_time', '>', $timeNow);
                                });
                            });
                    });
            });
    }

    /**
     * Dapatkan file cover foto (foto dokumentasi pertama atau foto thumbnail kegiatan).
     */
    public function getCoverPhotoAttribute(): ?string
    {
        if ($this->relationLoaded('fotos') && $this->fotos->isNotEmpty()) {
            $firstFoto = $this->fotos->first(fn ($f) => $f->isFoto());
            if ($firstFoto) {
                return $firstFoto->file_path;
            }
        }

        $firstFoto = $this->fotos()->where(function ($q) {
            $q->where('tipe', 'foto')->orWhereNull('tipe');
        })->first();

        if ($firstFoto) {
            return $firstFoto->file_path;
        }

        return $this->image;
    }

    /**
     * Dapatkan jumlah foto dokumentasi untuk kegiatan ini.
     */
    public function getFotoCountAttribute(): int
    {
        if ($this->relationLoaded('fotos')) {
            $count = $this->fotos->filter(fn ($f) => $f->isFoto())->count();
            if ($count === 0 && $this->image) {
                return 1;
            }

            return $count;
        }

        $count = $this->fotos()->where(function ($q) {
            $q->where('tipe', 'foto')->orWhereNull('tipe');
        })->count();

        return ($count === 0 && $this->image) ? 1 : $count;
    }

    /**
     * Dapatkan jumlah video dokumentasi untuk kegiatan ini.
     */
    public function getVideoCountAttribute(): int
    {
        if ($this->relationLoaded('fotos')) {
            return $this->fotos->filter(fn ($f) => $f->isVideo())->count();
        }

        return $this->fotos()->where('tipe', 'video')->count();
    }

    /**
     * Check if activity has valid map coordinates.
     */
    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    /**
     * Get Google Maps navigation URL for the activity location.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (! $this->hasCoordinates()) {
            return null;
        }

        return "https://www.google.com/maps/dir/?api=1&destination={$this->latitude},{$this->longitude}";
    }
}
