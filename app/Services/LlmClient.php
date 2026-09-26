<?php

namespace App\Services;

use Anthropic\Client;
use Anthropic\Core\Exceptions\APIConnectionException;
use Anthropic\Core\Exceptions\APIStatusException;
use Anthropic\Core\Exceptions\RateLimitException;
use RuntimeException;

/**
 * Satu-satunya pintu ke Claude API (SDK resmi anthropic-ai/sdk).
 * Controller tidak boleh memanggil API langsung; lewat RataAi.
 */
class LlmClient
{
    public function enabled(): bool
    {
        return ! config('rata.llm.fake') && filled(config('rata.llm.api_key'));
    }

    /**
     * Kirim prompt dan kembalikan objek JSON hasil parse.
     *
     * @throws RuntimeException bila API gagal atau respons bukan JSON valid
     */
    public function json(string $system, string $prompt): array
    {
        $text = $this->text($system, $prompt);

        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false) {
            throw new RuntimeException('Respons AI tidak berisi objek JSON.');
        }

        $data = json_decode(substr($text, $start, $end - $start + 1), true);
        if (! is_array($data)) {
            throw new RuntimeException('JSON dari AI tidak valid: '.json_last_error_msg());
        }

        return $data;
    }

    public function text(string $system, string $prompt): string
    {
        $client = new Client(
            apiKey: config('rata.llm.api_key'),
            requestOptions: ['timeout' => (float) config('rata.llm.timeout'), 'maxRetries' => 1],
        );

        try {
            $message = $client->messages->create(
                model: config('rata.llm.model'),
                maxTokens: config('rata.llm.max_tokens'),
                system: $system,
                messages: [['role' => 'user', 'content' => $prompt]],
            );
        } catch (RateLimitException $e) {
            throw new RuntimeException('AI sedang sibuk (rate limit).', previous: $e);
        } catch (APIStatusException $e) {
            throw new RuntimeException('AI menolak permintaan: '.($e->type?->value ?? $e->getMessage()), previous: $e);
        } catch (APIConnectionException $e) {
            throw new RuntimeException('Tidak bisa terhubung ke AI.', previous: $e);
        }

        if ($message->stopReason === 'refusal') {
            throw new RuntimeException('AI menolak menjawab permintaan ini.');
        }

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                return $block->text;
            }
        }

        throw new RuntimeException('Respons AI kosong.');
    }
}
