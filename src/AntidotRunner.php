<?php

declare(strict_types=1);

namespace Antidot\Runtime;

use Antidot\Framework\Application;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Symfony\Component\Runtime\RunnerInterface;

final class AntidotRunner implements RunnerInterface
{
    private ContainerInterface $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function run(): int
    {
        if (PHP_SAPI === 'cli') {
            return $this->runAsync($this->container);
        }

        return $this->runSync($this->container);
    }

    private function runAsync(ContainerInterface $container): int
    {
        $http = $container->get(HttpServer::class);
        $socket = $container->get(SocketServer::class);

        $http->listen($socket);

        return 0;
    }

    private function runSync(ContainerInterface $container): int
    {
        $application = $container->get(Application::class);
        $sapi = new SapiEmitter();
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        $sapi->emit($application->handle($creator->fromGlobals()));

        return 0;
    }
}
