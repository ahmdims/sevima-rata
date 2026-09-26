<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = ['classroom_id', 'topic', 'grade', 'notes', 'status', 'source', 'level_labels'];

    protected function casts(): array
    {
        return ['level_labels' => 'array'];
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('level')->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /** Deskripsi kompetensi sebuah level, mis. "Membandingkan pecahan". */
    public function levelLabel(int $level): ?string
    {
        return $this->level_labels[$level] ?? $this->level_labels[(string) $level] ?? null;
    }
}
