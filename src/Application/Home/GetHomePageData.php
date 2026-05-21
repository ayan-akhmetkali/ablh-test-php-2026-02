<?php

declare(strict_types=1);

namespace App\Application\Home;

use App\Cache\FileCache;
use App\Model\CategoryRepository;
use App\Model\PostRepository;

final class GetHomePageData
{
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly FileCache $cache
    ) {
    }

    /** @return array{categories: array<int, array<string, mixed>>} */
    public function execute(): array
    {
        $categories = $this->cache->remember('home.categories.latest', 60, function (): array {
            $categories = $this->categories->findAllWithPostsCount();

            foreach ($categories as &$category) {
                $category['latest_posts'] = $this->posts->findLatestByCategory((int) $category['id'], 3);
            }

            return $categories;
        });

        return ['categories' => $categories];
    }
}
