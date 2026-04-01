<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\CategoryRepository;
use App\Model\PostRepository;

final class SeoController
{
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts
    ) {
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Sitemap: /sitemap.xml\n";
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $base = rtrim((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080'), '/');
        $categories = $this->categories->findAllWithPostsCount();

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        echo '<url><loc>' . $base . '/</loc></url>';

        foreach ($categories as $category) {
            echo '<url><loc>' . $base . '/category/' . htmlspecialchars((string) $category['slug']) . '</loc></url>';
            $posts = $this->posts->findLatestByCategory((int) $category['id'], 100);

            foreach ($posts as $post) {
                echo '<url><loc>' . $base . '/post/' . htmlspecialchars((string) $post['slug']) . '</loc></url>';
            }
        }

        echo '</urlset>';
    }
}
