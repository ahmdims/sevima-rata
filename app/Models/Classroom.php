<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Classroom extends Model
{
    protected $fillable = ['name', 'subject', 'grade', 'code'];

    protected static function booted(): void
    {
        static::creating(function (Classroom $classroom) {
            $classroom->code ??= static::generateCode();
        });
    }

    /** Kode kelas tanpa karakter yang mudah tertukar (0/O, 1/I). */
    public static function generateCode(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $length = config('rata.assessment.class_code_length');

        do {
            $code = collect(range(1, $length))->map(fn () => $alphabet[random_int(0, strlen($alphabet) - 1)])->implode('');
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public static function findByCode(string $code): ?self
    {
        return static::where('code', Str::upper(trim($code)))->first();
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class)->latest();
    }

    public function activeAssessment(): HasOne
    {
        return $this->hasOne(Assessment::class)->where('status', 'published')->latestOfMany();
    }
}
