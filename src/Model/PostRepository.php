<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

final class PostRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function findLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.id, p.slug, p.title, p.description, p.image, p.views, p.published_at
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY p.published_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @return array{items: array<int, array<string, mixed>>, total: int}
     */
    public function findByCategoryPaginated(
        int $categoryId,
        string $sort,
        int $page,
        int $perPage
    ): array {
        $sortSql = $sort === 'views' ? 'p.views DESC, p.published_at DESC' : 'p.published_at DESC';
        $offset = max(0, ($page - 1) * $perPage);

        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*)
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id'
        );
        $countStmt->execute(['category_id' => $categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $sql = sprintf(
            'SELECT p.id, p.slug, p.title, p.description, p.image, p.views, p.published_at
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY %s
             LIMIT :limit OFFSET :offset',
            $sortSql
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
        ];
    }

    /** @return array<string, mixed>|false */
    public function findOneBySlug(string $slug): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, slug, title, description, content, image, views, published_at
             FROM posts
             WHERE slug = :slug
             LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch();
    }

    public function incrementViews(int $postId): void
    {
        $stmt = $this->pdo->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $postId]);
    }

    /** @return array<int, array<string, mixed>> */
    public function findSimilar(int $postId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT p.id, p.slug, p.title, p.description, p.image, p.views, p.published_at
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id IN (
                 SELECT category_id FROM post_categories WHERE post_id = :post_id
             )
             AND p.id <> :post_id
             ORDER BY p.published_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
