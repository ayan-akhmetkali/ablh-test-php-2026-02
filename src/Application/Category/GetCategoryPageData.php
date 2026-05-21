<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Cache\FileCache;
use App\Model\CategoryRepository;
use App\Model\PostRepository;
use App\Application\Shared\SortOptionFactory;
use App\Support\Paginator;

final class GetCategoryPageData
{
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly FileCache $cache
    ) {
    }

    /** @return array{category: array<string, mixed>|false, payload?: array<string, mixed>} */
    public function execute(string $slug, string $sort, int $requestedPage, int $perPage = 6): array
    {
        $category = $this->categories->findBySlug($slug);
        if ($category === false) {
            return ['category' => false];
        }

        $cacheKey = sprintf('category.%s.%s.%d', $slug, $sort, $requestedPage);
        $payload = $this->cache->remember($cacheKey, 60, function () use ($category, $sort, $requestedPage, $perPage): array {
            $result = $this->posts->findByCategoryPaginated((int) $category['id'], $sort, $requestedPage, $perPage);
            $totalPages = Paginator::totalPages((int) $result['total'], $perPage);
            $page = Paginator::clampPage($requestedPage, $totalPages);

            if ($page !== $requestedPage) {
                $result = $this->posts->findByCategoryPaginated((int) $category['id'], $sort, $page, $perPage);
            }

            return [
                'posts' => $result['items'],
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'sortOptions' => SortOptionFactory::categorySortOptions($sort),
            ];
        });

        return ['category' => $category, 'payload' => $payload];
    }
}
