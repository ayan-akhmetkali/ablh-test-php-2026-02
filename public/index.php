<?php

declare(strict_types=1);

use App\Cache\FileCache;
use App\Controller\CategoryController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\SeoController;
use App\Core\Router;
use App\Database\Connection;
use App\Http\Request;
use App\Model\CategoryRepository;
use App\Model\PostRepository;
use App\View\View;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/app.php';
$dbConfig = require dirname(__DIR__) . '/config/database.php';

$view = new View($config);
$pdo = Connection::make($dbConfig);
$request = new Request($_GET);
$cache = new FileCache($config['cache_path']);
$categoryRepository = new CategoryRepository($pdo);
$postRepository = new PostRepository($pdo);

$router = new Router();

$homeController = new HomeController($view, $categoryRepository, $postRepository, $cache);
$categoryController = new CategoryController($view, $categoryRepository, $postRepository, $request, $cache);
$postController = new PostController($view, $postRepository);
$errorController = new ErrorController($view);
$seoController = new SeoController($categoryRepository, $postRepository);

$router->get('/', [$homeController, 'index']);
$router->get('/category/{slug}', [$categoryController, 'show']);
$router->get('/post/{slug}', [$postController, 'show']);
$router->get('/robots.txt', [$seoController, 'robots']);
$router->get('/sitemap.xml', [$seoController, 'sitemap']);
$router->setNotFoundHandler([$errorController, 'notFound']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
