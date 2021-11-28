<?php

declare(strict_types=1);

namespace Antidot\Runtime;

use Antidot\Framework\Application;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
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
        /** @var HttpServer $http */
        $http = $container->get(HttpServer::class);
        /** @var SocketServer $socket */
        $socket = $container->get(SocketServer::class);
        $runner = new AsyncRunner($http, $socket);

        return $runner->run();
    }

    private function runSync(ContainerInterface $container): int
    {
        /** @var Application $application */
        $application = $container->get(Application::class);
        /** @var SapiEmitter $sapi */
        $sapi = $container->get(SapiEmitter::class);
        $runner = new SyncRunner($application, $sapi);

        return $runner->run();
    }
}
