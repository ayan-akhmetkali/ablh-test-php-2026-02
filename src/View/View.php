<?php

declare(strict_types=1);

namespace App\View;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;
    private string $appUrl;

    public function __construct(array $config)
    {
        $this->smarty = new Smarty();
        $this->appUrl = rtrim((string) ($config['app_url'] ?? 'http://localhost:8080'), '/');
        $this->smarty->assign('appUrl', $this->appUrl);
        $this->smarty->setTemplateDir($config['templates_path']);
        $this->smarty->setCompileDir($config['compile_path']);
        $this->smarty->setCacheDir($config['cache_path']);
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): void
    {
        if (isset($data['canonicalUrl']) && is_string($data['canonicalUrl'])) {
            $canonical = $data['canonicalUrl'];
            if (str_starts_with($canonical, '/')) {
                $data['canonicalUrl'] = $this->appUrl . $canonical;
            }
        }

        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}
