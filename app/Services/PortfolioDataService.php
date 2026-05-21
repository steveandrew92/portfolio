<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

class PortfolioDataService
{
    public function path(): string
    {
        return storage_path('app/portfolio.json');
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Cache::remember('portfolio.data', now()->addMinutes(5), function (): array {
            $path = $this->path();

            if (! is_readable($path)) {
                throw new RuntimeException("portfolio.json tidak ditemukan di: {$path}");
            }

            $json = file_get_contents($path);
            $data = json_decode($json ?: '', true);

            if (! is_array($data)) {
                throw new RuntimeException('portfolio.json tidak valid (JSON error).');
            }

            return $data;
        });
    }

    public function flushCache(): void
    {
        Cache::forget('portfolio.data');
    }
}
