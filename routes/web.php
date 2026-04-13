<?php

declare(strict_types=1);

use App\Core\Router;

/**
 * @param array<string, object> $controllers
 */
return static function (Router $router, array $controllers): void {
    $router->get('/', [$controllers['home'], 'index']);
    $router->get('/category/{slug}', [$controllers['category'], 'show']);
    $router->get('/post/{slug}', [$controllers['post'], 'show']);
    $router->get('/robots.txt', [$controllers['seo'], 'robots']);
    $router->get('/sitemap.xml', [$controllers['seo'], 'sitemap']);

    $router->setNotFoundHandler([$controllers['error'], 'notFound']);
};
