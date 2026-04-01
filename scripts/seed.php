<?php

declare(strict_types=1);

use App\Database\Connection;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dbConfig = require dirname(__DIR__) . '/config/database.php';
$pdo = Connection::make($dbConfig);

$categories = [
    ['slug' => 'hosting', 'name' => 'Hosting', 'description' => 'Новости и статьи о хостинге'],
    ['slug' => 'php', 'name' => 'PHP', 'description' => 'Практика разработки на PHP'],
    ['slug' => 'infra', 'name' => 'Infrastructure', 'description' => 'Инфраструктура и DevOps'],
];

$insertCategory = $pdo->prepare(
    'INSERT INTO categories (slug, name, description)
     VALUES (:slug, :name, :description)
     ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description)'
);

foreach ($categories as $category) {
    $insertCategory->execute($category);
}

$posts = [
    [
        'slug' => 'php-routing-basics',
        'title' => 'PHP Routing Basics',
        'description' => 'Как организовать базовый роутинг без фреймворка.',
        'content' => 'Текст статьи о роутинге...',
        'image' => '/images/php-routing.jpg',
        'views' => 120,
        'published_at' => '2026-03-20 10:00:00',
        'categories' => ['php'],
    ],
    [
        'slug' => 'mysql-indexes-practice',
        'title' => 'MySQL Indexes in Practice',
        'description' => 'Практический гайд по индексам в MySQL.',
        'content' => 'Текст статьи об индексах...',
        'image' => '/images/mysql-index.jpg',
        'views' => 240,
        'published_at' => '2026-03-25 09:30:00',
        'categories' => ['infra', 'hosting'],
    ],
    [
        'slug' => 'smarty-templates-quickstart',
        'title' => 'Smarty Quickstart',
        'description' => 'Как быстро стартовать с Smarty в чистом PHP.',
        'content' => 'Текст статьи по Smarty...',
        'image' => '/images/smarty.jpg',
        'views' => 95,
        'published_at' => '2026-03-28 15:15:00',
        'categories' => ['php'],
    ],
];

$insertPost = $pdo->prepare(
    'INSERT INTO posts (slug, title, description, content, image, views, published_at)
     VALUES (:slug, :title, :description, :content, :image, :views, :published_at)
     ON DUPLICATE KEY UPDATE
        title = VALUES(title),
        description = VALUES(description),
        content = VALUES(content),
        image = VALUES(image),
        views = VALUES(views),
        published_at = VALUES(published_at)'
);

$getCategoryId = $pdo->prepare('SELECT id FROM categories WHERE slug = :slug LIMIT 1');
$getPostId = $pdo->prepare('SELECT id FROM posts WHERE slug = :slug LIMIT 1');
$insertPostCategory = $pdo->prepare(
    'INSERT IGNORE INTO post_categories (post_id, category_id) VALUES (:post_id, :category_id)'
);

foreach ($posts as $post) {
    $insertPost->execute([
        'slug' => $post['slug'],
        'title' => $post['title'],
        'description' => $post['description'],
        'content' => $post['content'],
        'image' => $post['image'],
        'views' => $post['views'],
        'published_at' => $post['published_at'],
    ]);

    $getPostId->execute(['slug' => $post['slug']]);
    $postId = (int) $getPostId->fetchColumn();

    foreach ($post['categories'] as $categorySlug) {
        $getCategoryId->execute(['slug' => $categorySlug]);
        $categoryId = (int) $getCategoryId->fetchColumn();

        if ($postId > 0 && $categoryId > 0) {
            $insertPostCategory->execute([
                'post_id' => $postId,
                'category_id' => $categoryId,
            ]);
        }
    }
}

echo "Seed completed\n";
