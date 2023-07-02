<?php

declare(strict_types=1);

namespace Antidot\Test\Runtime;

use Antidot\Framework\Application;
use Antidot\Framework\Middleware\MiddlewareFactory;
use Antidot\Framework\Router\RouteFactory;
use Antidot\Framework\Router\Router;
use Antidot\Runtime\SyncRunner;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

final class SyncRunnerTest extends TestCase
{
    public function testItShouldEmitApplicationResponse(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('has')
            ->with('test_middleware')
            ->willReturn(true);
        $container->expects(self::once())
            ->method('get')
            ->with('test_middleware')
            ->willReturn($this->createMock(MiddlewareInterface::class));
        $middlewareFactory = new MiddlewareFactory($container);
        $sapi = $this->createMock(SapiEmitter::class);
        $sapi->expects(self::once())
            ->method('emit')
            ->with(self::isInstanceOf(ResponseInterface::class));
        $router = $this->createMock(Router::class);
        $application = new Application(
            $middlewareFactory,
            new RouteFactory(),
            $router
        );
        $application->pipe('test_middleware');

        $runner = new SyncRunner($application, $sapi);

        self::assertSame(0, $runner->run());
    }
}
