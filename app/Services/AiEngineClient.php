<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class AiEngineClient
{
    public function analyze(UploadedFile $file): array
    {
        $baseUrl = rtrim((string) config('services.ai_engine.url'), '/');
        $timeout = (int) config('services.ai_engine.timeout', 600);

        $path = $file->getRealPath() ?: $file->getPathname();
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException('Tidak bisa membaca file yang diunggah.');
        }

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->attach('file', $contents, $file->getClientOriginalName())
            ->post("{$baseUrl}/analyze");

        $response->throw();

        /** @var array<string, mixed> */
        return $response->json();
    }

    /**
     * @return array{ok: bool, payload?: array<string, mixed>, error?: string}
     */
    public function ping(): array
    {
        $baseUrl = rtrim((string) config('services.ai_engine.url'), '/');
        $timeout = min(15, (int) config('services.ai_engine.timeout', 600));

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->get($baseUrl.'/');

            if (! $response->successful()) {
                return [
                    'ok' => false,
                    'error' => 'AI engine responded with HTTP '.$response->status(),
                ];
            }

            return [
                'ok' => true,
                'payload' => $response->json() ?? [],
            ];
        } catch (RequestException $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
