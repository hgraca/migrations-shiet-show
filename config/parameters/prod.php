<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();

    $parameters->set('graphql.debug', false);
};
