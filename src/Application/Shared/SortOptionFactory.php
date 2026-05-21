<?php

declare(strict_types=1);

namespace App\Application\Shared;

final class SortOptionFactory
{
    /** @return array<int, array{label: string, value: string, isActive: bool}> */
    public static function categorySortOptions(string $currentSort): array
    {
        return [
            ['label' => 'По дате', 'value' => 'date', 'isActive' => $currentSort === 'date'],
            ['label' => 'По просмотрам', 'value' => 'views', 'isActive' => $currentSort === 'views'],
        ];
    }
}
