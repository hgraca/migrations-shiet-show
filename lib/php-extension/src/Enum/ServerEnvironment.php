<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Enum;

/**
 * @method static self local()
 * @method bool   isLocal()
 * @method static self development()
 * @method bool   isDevelopment()
 * @method static self acceptance()
 * @method bool   isAcceptance()
 * @method static self staging()
 * @method bool   isStaging()
 * @method static self demo()
 * @method bool   isDemo()
 * @method static self production()
 * @method bool   isProduction()
 */
final class ServerEnvironment extends AbstractEnum
{
    protected const LOCAL = 'local'; // dev machine bare bones, outside any runtime container (docker, vagrant, LXC, ...)
    protected const DEVELOPMENT = 'dev'; // dev machine inside a runtime container (docker, vagrant, LXC, ...)
    protected const ACCEPTANCE = 'acc'; // Testing in the CI.
    protected const STAGING = 'stg'; // Server for manual testing, for the PO to do QA. An instance per MR.
    protected const DEMO = 'demo'; // Demo server with a prod instance of the application for sales, training, etc.
    protected const PRODUCTION = 'prod'; // Production server.
}
