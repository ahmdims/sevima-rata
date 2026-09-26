<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = ['assessment_id', 'level', 'order', 'stem', 'options', 'answer_key', 'explanation'];

    protected function casts(): array
    {
        return ['options' => 'array', 'level' => 'integer'];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function isCorrect(string $key): bool
    {
        return strtoupper($key) === strtoupper($this->answer_key);
    }

    public function misconceptionFor(string $key): ?string
    {
        foreach ($this->options as $option) {
            if (strtoupper($option['key']) === strtoupper($key)) {
                return $option['misconception'] ?? null;
            }
        }

        return null;
    }

    public function hasOption(string $key): bool
    {
        return collect($this->options)->contains(fn ($option) => strtoupper($option['key']) === strtoupper($key));
    }
}
