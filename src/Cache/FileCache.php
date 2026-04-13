<?php

declare(strict_types=1);

namespace App\Cache;

final class FileCache
{
    public function __construct(private readonly string $cacheDir)
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    public function remember(string $key, int $ttlSeconds, callable $callback): mixed
    {
        $path = $this->path($key);

        if (is_file($path)) {
            $payload = @unserialize((string) file_get_contents($path));
            if (is_array($payload) && isset($payload['expires_at'], $payload['value'])) {
                if ((int) $payload['expires_at'] >= time()) {
                    return $payload['value'];
                }
            }
        }

        $value = $callback();

        $payload = serialize([
            'expires_at' => time() + $ttlSeconds,
            'value' => $value,
        ]);

        file_put_contents($path, $payload, LOCK_EX);

        return $value;
    }

    private function path(string $key): string
    {
        return rtrim($this->cacheDir, '/\\') . DIRECTORY_SEPARATOR . md5($key) . '.cache';
    }
}
