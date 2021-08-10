<?php

declare(strict_types=1);

$path = dirname(__DIR__) . '/var/cache/prod/App_KernelProdContainer.preload.php';

if (file_exists($path)) {
    require $path;
}
