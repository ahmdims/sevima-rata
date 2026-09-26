<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Support\Facades\DB;

/**
 * Menyimpan payload diagnostik (hasil AI atau fixture) ke tabel questions.
 * Payload sudah divalidasi oleh DiagnosticValidator sebelum sampai sini.
 */
class DiagnosticImporter
{
    public function import(Assessment $assessment, array $payload): Assessment
    {
        return DB::transaction(function () use ($assessment, $payload) {
            $assessment->questions()->delete();

            $labels = [];
            foreach ($payload['levels'] as $level) {
                $labels[$level['level']] = $level['label'];

                foreach (array_values($level['questions']) as $order => $question) {
                    $assessment->questions()->create([
                        'level' => $level['level'],
                        'order' => $order + 1,
                        'stem' => $question['stem'],
                        'options' => array_map(fn ($option) => [
                            'key' => strtoupper($option['key']),
                            'text' => $option['text'],
                            'misconception' => $option['misconception'] ?: null,
                        ], $question['options']),
                        'answer_key' => strtoupper($question['answer_key']),
                        'explanation' => $question['explanation'] ?? null,
                    ]);
                }
            }

            $assessment->update(['level_labels' => $labels]);

            return $assessment->fresh('questions');
        });
    }

    public static function fixture(string $name): array
    {
        return json_decode(file_get_contents(database_path("fixtures/$name.json")), true, flags: JSON_THROW_ON_ERROR);
    }
}
