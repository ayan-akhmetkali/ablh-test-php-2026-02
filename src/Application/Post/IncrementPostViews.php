<?php

declare(strict_types=1);

namespace App\Application\Post;

use App\Model\PostRepository;

final class IncrementPostViews
{
    public function __construct(private readonly PostRepository $posts)
    {
    }

    public function execute(int $postId): void
    {
        $this->posts->incrementViews($postId);
    }
}
