<?php

declare(strict_types=1);

use Acme\App\Core\Component\Channel\Application\Repository\Database\ChannelRepository;
use Acme\App\Core\Component\Client\Application\Repository\Database\ClientConfigRepository;
use Acme\App\Core\Port\CommandBus\CommandDispatcherInterface;
use Acme\App\Core\Port\Conversion\ConversionApiInterface;
use Acme\App\Core\Port\CustomerDataPlatform\CdpClientInterface;
use Acme\App\Core\Port\EmailCampaigns\Acme\Emails\Triggered\TriggeredEmailClientInterface;
use Acme\App\Core\Port\Sftp\SftpClientInterface;
use Acme\App\Infrastructure\CommandBus\Symfony\CommandDispatcher;
use Acme\App\Presentation\Api\Rest\Pub\Client\ClientsInRangeQuery;
use Acme\App\Test\Framework\TestDouble\FakeCdpClient;
use Acme\App\Test\Framework\TestDouble\NullTriggeredEmailClient;
use Acme\App\Test\Framework\TestDouble\SpyConversionApiClient;
use Acme\App\Test\Framework\TestDouble\SpySftpClientAdapter;
use Acme\PhpExtension\Filesystem\FilesystemInterface;
use Acme\PhpExtension\Filesystem\InMemoryFilesystem;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();
};
