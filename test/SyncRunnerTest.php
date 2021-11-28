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
use Psr\Http\Message\ResponseInterface;

final class SyncRunnerTest extends TestCase
{
    public function testItShouldEmitApplicationResponse(): void
    {
        $middlewareFactory = $this->createMock(MiddlewareFactory::class);
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
