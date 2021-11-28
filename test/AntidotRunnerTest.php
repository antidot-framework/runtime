<?php

declare(strict_types=1);

namespace Antidot\Test\Runtime;

use Antidot\Runtime\AntidotRunner;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use React\EventLoop\LoopInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use RingCentral\Psr7\Response;

final class AntidotRunnerTest extends TestCase
{
    public function testItShouldRunAntidotFrameworkApplication(): void
    {
        $loop = $this->createMock(LoopInterface::class);
        $server = new HttpServer(
            $loop,
            function () {
                return new Response(200, [], '');
            }
        );
        $socket = new SocketServer('0.0.0.0:1234', [], $loop);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([HttpServer::class], [SocketServer::class])
            ->willReturnOnConsecutiveCalls($server, $socket);
        $runner = new AntidotRunner($container);
        self::assertSame(0, $runner->run());
    }
}
