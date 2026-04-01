<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\View;

final class ErrorController
{
    public function __construct(private readonly View $view)
    {
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->view->render('404.tpl', [
            'title' => '404 Not Found',
            'message' => 'Страница не найдена.',
        ]);
    }
}
