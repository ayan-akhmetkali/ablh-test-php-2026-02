<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Post\GetPostPageData;
use App\Application\Post\IncrementPostViews;
use App\View\View;

final class PostController
{
    public function __construct(
        private readonly View $view,
        private readonly GetPostPageData $getPostPageData,
        private readonly IncrementPostViews $incrementPostViews
    ) {
    }

    public function show(string $slug): void
    {
        $result = $this->getPostPageData->execute($slug);
        $post = $result['post'];

        if ($post === false) {
            http_response_code(404);
            $this->view->render('404.tpl', [
                'title' => '404 Not Found',
                'message' => 'Статья не найдена.',
            ]);
            return;
        }

        $this->incrementPostViews->execute((int) $post['id']);
        $post['views'] = (int) $post['views'] + 1;

        $this->view->render('post.tpl', [
            'title' => $post['title'],
            'post' => $post,
            'similarPosts' => $result['similarPosts'],
            'canonicalUrl' => '/post/' . rawurlencode((string) $post['slug']),
            'ogType' => 'article',
            'metaDescription' => (string) ($post['description'] ?? 'Статья блога'),
        ]);
    }
}
