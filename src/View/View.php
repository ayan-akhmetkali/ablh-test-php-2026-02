<?php

declare(strict_types=1);

namespace App\View;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(array $config)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($config['templates_path']);
        $this->smarty->setCompileDir($config['compile_path']);
        $this->smarty->setCacheDir($config['cache_path']);
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}
