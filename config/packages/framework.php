<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'secret' => '%env(GT_APP_SECRET)%',
        //csrf_protection: true
        //http_method_override: true

        // Enables session support. Note that the session will ONLY be started if you read or write from it.
        // Remove or comment this section to explicitly disable session support.
        'session' => [
            'handler_id' => null,
            'cookie_secure' => 'auto',
            'cookie_samesite' => 'lax',
        ],

        //esi: true
        //fragments: true
        'php_errors' => [
            'log' => true,
        ],
    ]);
};
