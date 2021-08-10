<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $container->extension('security', [
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'anonymous' => true,
                'lazy' => true,
//                'provider' => 'security_user_provider',

                // activate different ways to authenticate
                // https://symfony.com/doc/current/security.html#firewalls-authentication

                // https://symfony.com/doc/current/security/impersonating_user.html
                // switch_user: true
            ],
        ],
        // Easy way to control access for large sections of your site
        // Note: Only the *first* access control that matches will be used
        'access_control' => [
            [
                'path' => '^/',
                'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY',
            ],
        ],
    ]);
};
