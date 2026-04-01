<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function findAllWithPostsCount(): array
    {
        $sql = <<<SQL
            SELECT c.id, c.slug, c.name, c.description, COUNT(pc.post_id) AS posts_count
            FROM categories c
            INNER JOIN post_categories pc ON pc.category_id = c.id
            INNER JOIN posts p ON p.id = pc.post_id
            WHERE p.published_at IS NOT NULL
            GROUP BY c.id, c.slug, c.name, c.description
            ORDER BY c.name ASC
        SQL;

        return $this->pdo->query($sql)->fetchAll();
    }

    /** @return array<string, mixed>|false */
    public function findBySlug(string $slug): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, slug, name, description FROM categories WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch();
    }
}
