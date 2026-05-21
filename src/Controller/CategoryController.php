<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Category\GetCategoryPageData;
use App\Http\Request;
use App\View\View;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly GetCategoryPageData $getCategoryPageData
    ) {
    }

    public function show(string $slug): void
    {
        $sort = $this->request->sort();
        $requestedPage = $this->request->page();

        $result = $this->getCategoryPageData->execute($slug, $sort, $requestedPage);
        $category = $result['category'];

        if ($category === false) {
            http_response_code(404);
            $this->view->render('404.tpl', [
                'title' => '404 Not Found',
                'message' => 'Категория не найдена.',
            ]);
            return;
        }

        $payload = $result['payload'];
        if ((int) $payload['currentPage'] !== $requestedPage) {
            $target = sprintf('/category/%s?sort=%s&page=%d', rawurlencode($slug), $sort, (int) $payload['currentPage']);
            header('Location: ' . $target, true, 302);
            return;
        }

        $this->view->render('category.tpl', [
            'title' => 'Категория: ' . $category['name'],
            'category' => $category,
            'posts' => $payload['posts'],
            'sortOptions' => $payload['sortOptions'],
            'currentSort' => $sort,
            'currentPage' => $payload['currentPage'],
            'totalPages' => $payload['totalPages'],
            'metaDescription' => 'Категория ' . $category['name'] . ': список статей с сортировкой и пагинацией.',
            'canonicalUrl' => sprintf('/category/%s?sort=%s&page=%d', rawurlencode($slug), $sort, (int) $payload['currentPage']),
        ]);
    }
}
