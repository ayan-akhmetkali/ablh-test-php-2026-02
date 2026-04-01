<?php

declare(strict_types=1);

namespace App\Support;

final class Paginator
{
    public static function totalPages(int $totalItems, int $perPage): int
    {
        if ($perPage <= 0) {
            return 1;
        }

        return max(1, (int) ceil($totalItems / $perPage));
    }

    public static function clampPage(int $page, int $totalPages): int
    {
        return min(max(1, $page), max(1, $totalPages));
    }
}
