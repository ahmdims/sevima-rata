<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    protected $fillable = ['assessment_id', 'student_name', 'level', 'misconceptions', 'feedback', 'finished_at'];

    protected function casts(): array
    {
        return [
            'misconceptions' => 'array',
            'finished_at' => 'datetime',
            'level' => 'integer',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function isFinished(): bool
    {
        return $this->finished_at !== null;
    }

    /** Miskonsepsi yang paling sering muncul pada siswa ini. */
    public function topMisconception(): ?string
    {
        return array_key_first($this->misconceptions ?? []);
    }
}
