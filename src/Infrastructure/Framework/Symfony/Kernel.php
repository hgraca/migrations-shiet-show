<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Framework\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const ENV_PROD = 'prod';
    private const CONFIG_FILE_EXTENSIONS = '{yaml,php}';

    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }

    protected function build(ContainerBuilder $container): void
    {
        $this->registerCompilerPasses($container);
    }

    private function registerCompilerPasses(ContainerBuilder $container): void
    {
        /**
         * @var array<class-string<CompilerPassInterface>, array<string, bool>> $contents
         * @psalm-suppress UnresolvableInclude
         */
        $contents = require $this->getConfigDir() . '/compiler_pass.php';

        foreach ($contents as $compilerPass => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                $container->addCompilerPass(new $compilerPass());
            }
        }
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();

        $this->configurePackages($container, $configDir);
        $this->configureParameters($container, $configDir);
        $this->configureServices($container, $configDir);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();

        $routes->import(sprintf('%s/{routes}/%s/*.%s', $configDir, $this->environment, self::CONFIG_FILE_EXTENSIONS));
        $routes->import(sprintf('%s/{routes}/*.%s', $configDir, self::CONFIG_FILE_EXTENSIONS));

        if (is_file($path = $configDir . '/routes.php')) {
            /** @psalm-suppress UnresolvableInclude */
            (require $path)($routes->withPath($path), $this);
        }
    }

    private function configurePackages(ContainerConfigurator $container, string $configDir): void
    {
        $container->import(sprintf('%s/{packages}/*.%s', $configDir, self::CONFIG_FILE_EXTENSIONS));
        $container->import(sprintf('%s/{packages}/%s/*.%s', $configDir, $this->environment, self::CONFIG_FILE_EXTENSIONS));
    }

    private function configureParameters(ContainerConfigurator $container, string $configDir): void
    {
        $environmentList = array_unique([self::ENV_PROD, $this->environment]);

        foreach ($environmentList as $environment) {
            $container->import(sprintf('%s/{parameters}/{%s}.php', $configDir, $environment));
            $container->import(sprintf('%s/{parameters}/{%s}/**/*.php', $configDir, $environment));
        }
    }

    private function configureServices(ContainerConfigurator $container, string $configDir): void
    {
        $environmentList = array_unique([self::ENV_PROD, $this->environment]);

        foreach ($environmentList as $environment) {
            $container->import(sprintf('%s/services/{%s}.php', $configDir, $environment));
            $container->import(sprintf('%s/services/{%s}/**/*.php', $configDir, $environment));
        }
    }
}
