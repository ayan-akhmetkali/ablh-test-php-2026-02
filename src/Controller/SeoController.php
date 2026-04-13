<?php

declare(strict_types=1);

namespace App\Controller;

use App\Cache\FileCache;
use App\Model\CategoryRepository;
use App\Model\PostRepository;

final class SeoController
{
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly FileCache $cache,
        private readonly string $appUrl
    ) {
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=3600');

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo 'Sitemap: ' . $this->appUrl . "/sitemap.xml\n";
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=600');

        $xml = $this->cache->remember('seo.sitemap.xml', 600, function (): string {
            $categories = $this->categories->findAllWithPostsCount();
            $seenPostSlugs = [];

            $lines = [];
            $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $lines[] = '  <url><loc>' . $this->escapeXml($this->appUrl . '/') . '</loc></url>';

            foreach ($categories as $category) {
                $categoryUrl = $this->appUrl . '/category/' . rawurlencode((string) $category['slug']);
                $lines[] = '  <url><loc>' . $this->escapeXml($categoryUrl) . '</loc></url>';

                $posts = $this->posts->findLatestByCategory((int) $category['id'], 100);
                foreach ($posts as $post) {
                    $slug = (string) $post['slug'];
                    if (isset($seenPostSlugs[$slug])) {
                        continue;
                    }

                    $seenPostSlugs[$slug] = true;
                    $postUrl = $this->appUrl . '/post/' . rawurlencode($slug);
                    $lines[] = '  <url><loc>' . $this->escapeXml($postUrl) . '</loc></url>';
                }
            }

            $lines[] = '</urlset>';

            return implode("\n", $lines);
        });

        echo $xml;
    }

    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
