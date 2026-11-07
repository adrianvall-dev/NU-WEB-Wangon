<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FotoKegiatan extends Model
{
    use HasFactory;

    protected $table = 'foto_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'file_path',
        'tipe',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function isVideo(): bool
    {
        return $this->tipe === 'video';
    }

    public function isFoto(): bool
    {
        return $this->tipe === 'foto' || empty($this->tipe);
    }

    public function scopeFoto($query)
    {
        return $query->where(function ($q) {
            $q->where('tipe', 'foto')->orWhereNull('tipe');
        });
    }

    public function scopeVideo($query)
    {
        return $query->where('tipe', 'video');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}
