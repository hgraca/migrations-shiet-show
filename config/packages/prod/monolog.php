<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $container->extension('monolog', [
        'handlers' => [
            'main' => [
                'type' => 'fingers_crossed',
                'action_level' => 'error', // i.e.: var/log/dev.log
                'handler' => 'main_nested',
                'excluded_http_codes' => [404, 405],
                'buffer_size' => 50, // How many messages should be saved? Prevent memory leaks
            ],
            'main_nested' => [
                'type' => 'stream',
                'path' => '%kernel.logs_dir%/%kernel.environment%.log', // i.e.: var/log/dev.log
                'level' => 'info',
                'formatter' => 'monolog.formatter.json',
            ],
            'stdout' => [ // So docker receives the logs as well:
                'type' => 'fingers_crossed',
                'action_level' => 'error',
                'level' => 'debug',
                'handler' => 'stdout_nested',
                'excluded_http_codes' => [404, 405],
            ],
            'stdout_nested' => [
                'type' => 'stream',
                'path' => 'php://stdout',
                'level' => 'info',
                'formatter' => 'monolog.formatter.json',
            ],
            'console' => [
                'type' => 'console',
                'process_psr_3_messages' => false,
                'channels' => ['!event', '!doctrine'],
                'formatter' => 'monolog.formatter.json',
            ],
        ],
    ]);
};
