<?php

declare(strict_types=1);

namespace Antidot\Runtime;

use React\Http\HttpServer;
use React\Socket\SocketServer;

final class AsyncRunner
{
    private HttpServer $httpServer;
    private SocketServer $socketServer;

    public function __construct(HttpServer $httpServer, SocketServer $socketServer)
    {

        $this->httpServer = $httpServer;
        $this->socketServer = $socketServer;
    }

    public function run(): int
    {
        $this->httpServer->listen($this->socketServer);

        return 0;
    }
}
