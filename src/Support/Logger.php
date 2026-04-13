<?php

declare(strict_types=1);

namespace App\Support;

final class Logger
{
    public function __construct(private readonly string $logFile)
    {
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }

    /** @param array<string, mixed> $context */
    public function error(string $message, array $context = []): void
    {
        $line = sprintf(
            "[%s] ERROR %s %s\n",
            date('c'),
            $message,
            $context !== [] ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ''
        );

        file_put_contents($this->logFile, $line, FILE_APPEND | LOCK_EX);
    }
}
