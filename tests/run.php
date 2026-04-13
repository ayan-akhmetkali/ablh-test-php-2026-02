<?php

declare(strict_types=1);

use App\Cache\FileCache;
use App\Http\Request;
use App\Support\Paginator;

require_once dirname(__DIR__) . '/src/Http/Request.php';
require_once dirname(__DIR__) . '/src/Support/Paginator.php';
require_once dirname(__DIR__) . '/src/Cache/FileCache.php';

/**
 * @param bool $condition
 */
function expect(bool $condition, string $message): void
{
    if (!$condition) {
        fwrite(STDERR, "[FAIL] {$message}\n");
        exit(1);
    }

    fwrite(STDOUT, "[OK] {$message}\n");
}

function testRequest(): void
{
    $request = new Request(['sort' => 'views', 'page' => '3']);
    expect($request->sort() === 'views', 'Request::sort returns views for valid sort');
    expect($request->page() === 3, 'Request::page parses numeric page');

    $requestInvalid = new Request(['sort' => 'invalid', 'page' => '-10']);
    expect($requestInvalid->sort() === 'date', 'Request::sort falls back to date');
    expect($requestInvalid->page() === 1, 'Request::page clamps page >= 1');
}

function testPaginator(): void
{
    expect(Paginator::totalPages(0, 10) === 1, 'Paginator::totalPages returns at least 1');
    expect(Paginator::totalPages(25, 10) === 3, 'Paginator::totalPages computes ceil');
    expect(Paginator::clampPage(-10, 5) === 1, 'Paginator::clampPage lower bound');
    expect(Paginator::clampPage(99, 5) === 5, 'Paginator::clampPage upper bound');
}

function testFileCache(): void
{
    $cacheDir = sys_get_temp_dir() . '/ablh-stage8-cache-' . bin2hex(random_bytes(4));
    $cache = new FileCache($cacheDir);

    $calls = 0;
    $value1 = $cache->remember('demo', 5, function () use (&$calls): array {
        $calls++;

        return ['value' => 42];
    });

    $value2 = $cache->remember('demo', 5, function () use (&$calls): array {
        $calls++;

        return ['value' => 99];
    });

    expect($value1['value'] === 42, 'FileCache::remember returns first computed value');
    expect($value2['value'] === 42, 'FileCache::remember returns cached value within ttl');
    expect($calls === 1, 'FileCache::remember executes callback once within ttl');
}

testRequest();
testPaginator();
testFileCache();

fwrite(STDOUT, "\nAll stage-8 smoke tests passed.\n");
