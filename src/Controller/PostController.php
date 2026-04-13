<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\PostRepository;
use App\View\View;

final class PostController
{
    public function __construct(
        private readonly View $view,
        private readonly PostRepository $posts
    ) {
    }

    public function show(string $slug): void
    {
        $post = $this->posts->findOneBySlug($slug);
        if ($post === false) {
            http_response_code(404);
            $this->view->render('404.tpl', [
                'title' => '404 Not Found',
                'message' => 'Статья не найдена.',
            ]);
            return;
        }

        $this->posts->incrementViews((int) $post['id']);
        $post['views'] = (int) $post['views'] + 1;
        $similar = $this->posts->findSimilar((int) $post['id'], 3);

        $this->view->render('post.tpl', [
            'title' => $post['title'],
            'post' => $post,
            'similarPosts' => $similar,
            'canonicalUrl' => '/post/' . rawurlencode((string) $post['slug']),
            'ogType' => 'article',
            'metaDescription' => (string) ($post['description'] ?? 'Статья блога'),
        ]);
    }
}
