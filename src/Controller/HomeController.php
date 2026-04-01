<?php

declare(strict_types=1);

namespace App\Controller;

use App\Cache\FileCache;
use App\Model\CategoryRepository;
use App\Model\PostRepository;
use App\View\View;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly FileCache $cache
    ) {
    }

    public function index(): void
    {
        $categories = $this->cache->remember('home.categories.latest', 60, function (): array {
            $categories = $this->categories->findAllWithPostsCount();

            foreach ($categories as &$category) {
                $category['latest_posts'] = $this->posts->findLatestByCategory((int) $category['id'], 3);
            }

            return $categories;
        });

        $this->view->render('home.tpl', [
            'title' => 'Главная',
            'categories' => $categories,
            'metaDescription' => 'Блог с категориями и статьями на PHP + MySQL + Smarty.',
        ]);
    }
}
