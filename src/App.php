<?php

declare(strict_types=1);

namespace App;

use App\Application\Category\GetCategoryPageData;
use App\Application\Home\GetHomePageData;
use App\Application\Post\GetPostPageData;
use App\Application\Post\IncrementPostViews;
use App\Cache\FileCache;
use App\Controller\CategoryController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\SeoController;
use App\Core\Router;
use App\Database\Connection;
use App\Support\Logger;
use App\Http\Request;
use App\Model\CategoryRepository;
use App\Model\PostRepository;
use App\View\View;

final class App
{
    /** @var array<string, mixed> */
    private array $config;

    /** @var array<string, mixed> */
    private array $dbConfig;

    public function __construct()
    {
        $this->config = require dirname(__DIR__) . '/config/app.php';
        $this->dbConfig = require dirname(__DIR__) . '/config/database.php';
    }

    public function run(string $method, string $uri): void
    {
        $view = new View($this->config);
        $logger = new Logger($this->config['cache_path'] . '/app.log');
        $requestId = bin2hex(random_bytes(8));

        header('X-Request-Id: ' . $requestId);
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: no-referrer-when-downgrade');

        try {
            $pdo = Connection::make($this->dbConfig);
            $request = new Request($_GET);
            $cache = new FileCache($this->config['cache_path']);
            $categoryRepository = new CategoryRepository($pdo);
            $postRepository = new PostRepository($pdo);

            $homeUseCase = new GetHomePageData($categoryRepository, $postRepository, $cache);
            $categoryUseCase = new GetCategoryPageData($categoryRepository, $postRepository, $cache);
            $postUseCase = new GetPostPageData($postRepository);
            $incrementPostViews = new IncrementPostViews($postRepository);

            $homeController = new HomeController($view, $homeUseCase);
            $categoryController = new CategoryController($view, $request, $categoryUseCase);
            $postController = new PostController($view, $postUseCase, $incrementPostViews);
            $errorController = new ErrorController($view);
            $seoController = new SeoController($categoryRepository, $postRepository, $cache, (string) $this->config['app_url']);

            $router = new Router();

            $registerRoutes = require dirname(__DIR__) . '/routes/web.php';
            $registerRoutes($router, [
                'home' => $homeController,
                'category' => $categoryController,
                'post' => $postController,
                'error' => $errorController,
                'seo' => $seoController,
            ]);

            $router->dispatch($method, $uri);
        } catch (\Throwable $e) {
            $logger->error('Unhandled exception', [
                'request_id' => $requestId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            http_response_code(500);
            $view->render('500.tpl', [
                'title' => '500 Internal Server Error',
                'metaDescription' => 'Внутренняя ошибка сервера.',
                'requestId' => $requestId,
                'canonicalUrl' => '/',
            ]);
        }
    }
}
