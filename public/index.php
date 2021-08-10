<?php

declare(strict_types=1);

use Acme\App\Infrastructure\Framework\Symfony\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv('GT_APP_ENV', 'GT_APP_DEBUG'))->bootEnv(dirname(__DIR__) . '/.env');

if ($_SERVER['GT_APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

$kernel = new Kernel((string) $_SERVER['GT_APP_ENV'], (bool) $_SERVER['GT_APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
