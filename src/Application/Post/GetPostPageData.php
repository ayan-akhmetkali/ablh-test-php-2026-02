<?php

declare(strict_types=1);

namespace App\Application\Post;

use App\Model\PostRepository;

final class GetPostPageData
{
    public function __construct(private readonly PostRepository $posts)
    {
    }

    /** @return array{post: array<string, mixed>|false, similarPosts?: array<int, array<string, mixed>>} */
    public function execute(string $slug): array
    {
        $post = $this->posts->findOneBySlug($slug);
        if ($post === false) {
            return ['post' => false];
        }

        return [
            'post' => $post,
            'similarPosts' => $this->posts->findSimilar((int) $post['id'], 3),
        ];
    }
}
