<?php

declare(strict_types=1);

use Acme\App\Infrastructure\Authentication\SymfonySecurityBundle\SecurityUserProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $container->extension('gesdinet_jwt_refresh_token', [
        'single_use' => true,
        'user_provider' => SecurityUserProvider::class,
    ]);
};
