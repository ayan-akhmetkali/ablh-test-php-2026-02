<?php

declare(strict_types=1);

namespace App\Http;

final class Request
{
    public function __construct(private readonly array $query)
    {
    }

    public function sort(): string
    {
        $sort = $this->query['sort'] ?? 'date';
        return $sort === 'views' ? 'views' : 'date';
    }

    public function page(): int
    {
        $page = (int) ($this->query['page'] ?? 1);
        return max(1, $page);
    }
}
