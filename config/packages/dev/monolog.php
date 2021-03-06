<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

//Monolog supports the logging levels described by RFC 5424. https://github.com/Seldaek/monolog/blob/main/doc/01-usage.md#log-levels
//    DEBUG (100):       Detailed debug information.
//    INFO (200):        Interesting events. Examples: User logs in, SQL logs.
//    NOTICE (250):      Normal but significant events.
//    WARNING (300):     Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
//    ERROR (400):       Runtime errors that do not require immediate action but should typically be logged and monitored.
//    CRITICAL (500):    Critical conditions. Example: Application component unavailable, unexpected exception.
//    ALERT (550):       Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
//    EMERGENCY (600):   Emergency: system is unusable.
return function (ContainerConfigurator $container): void {
    $container->extension('monolog', [
        'handlers' => [
            'main' => [
                'type' => 'stream',
                'path' => '%kernel.logs_dir%/%kernel.environment%.log', // i.e.: var/log/dev.log
                'level' => 'debug',
                'channels' => ['!event'],
                'formatter' => 'monolog.formatter.line',
            ],
            'stdout' => [ // So docker receives the logs as well:
                'type' => 'stream',
                'path' => 'php://stdout',
                'level' => 'debug',
                'channels' => ['!event'],
                'formatter' => 'monolog.formatter.line',
            ],
            // uncomment to get logging in your browser
            // you may have to allow bigger header sizes in your Web server configuration
//            'firephp' => [
//                'type' => 'firephp',
//                'level' => 'info',
//            ],
//            'chromephp' => [
//                'type' => 'chromephp',
//                'level' => 'info',
//            ],
//            'console' => [
//                'type' => 'console',
//                'process_psr_3_messages' => false,
//                'channels' => ["!event", "!doctrine", "!console"],
//                'formatter' => 'monolog.formatter.line',
//            ],
        ],
    ]);
};
