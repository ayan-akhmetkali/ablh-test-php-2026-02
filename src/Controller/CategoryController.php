<?php

declare(strict_types=1);

namespace App\Controller;

use App\Cache\FileCache;
use App\Http\Request;
use App\Model\CategoryRepository;
use App\Model\PostRepository;
use App\Support\Paginator;
use App\View\View;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly Request $request,
        private readonly FileCache $cache
    ) {
    }

    public function show(string $slug): void
    {
        $category = $this->categories->findBySlug($slug);
        if ($category === false) {
            http_response_code(404);
            echo 'Category not found';
            return;
        }

        $sort = $this->request->sort();
        $requestedPage = $this->request->page();
        $perPage = 6;

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
                'page' => $page,
                'totalPages' => $totalPages,
            ];
        });

        $this->view->render('category.tpl', [
            'title' => 'Категория: ' . $category['name'],
            'category' => $category,
            'posts' => $payload['posts'],
            'sort' => $sort,
            'currentPage' => $payload['page'],
            'totalPages' => $payload['totalPages'],
            'metaDescription' => 'Категория ' . $category['name'] . ': список статей с сортировкой и пагинацией.',
        ]);
    }
}
