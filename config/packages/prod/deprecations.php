<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

// As of Symfony 5.1, deprecations are logged in the dedicated "deprecation" channel when it exists
return function (ContainerConfigurator $container): void {
//    $container->extension('monolog', [
//        'channels' => ['deprecation'],
//        'handlers' => [
//            'deprecation' => [
//                'type' => 'stream',
//                'channels' => ['deprecation'],
//                'path' => 'php://stderr',
//            ],
//        ],
//    ]);
};
