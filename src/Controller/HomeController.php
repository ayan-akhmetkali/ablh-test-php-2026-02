<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Home\GetHomePageData;
use App\View\View;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly GetHomePageData $getHomePageData
    ) {
    }

    public function index(): void
    {
        $homeData = $this->getHomePageData->execute();

        $this->view->render('home.tpl', [
            'title' => 'Главная',
            'categories' => $homeData['categories'],
            'metaDescription' => 'Блог с категориями и статьями на PHP + MySQL + Smarty.',
            'canonicalUrl' => '/',
        ]);
    }
}
