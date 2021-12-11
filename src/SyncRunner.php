<?php

declare(strict_types=1);

namespace Antidot\Runtime;

use Antidot\Framework\Application;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use React\EventLoop\Loop;

final class SyncRunner
{
    private Application $application;
    private SapiEmitter $sapi;
    private ServerRequestCreator $responseFactory;

    public function __construct(Application $application, SapiEmitter $sapi)
    {
        $this->application = $application;
        $this->sapi = $sapi;
        $psr17Factory = new Psr17Factory();
        $this->responseFactory = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );
    }

    public function run(): int
    {
        $this->sapi->emit(
            $this->application->handle($this->responseFactory->fromGlobals())
        );

        $loop = Loop::get();
        $loop->run();

        return 0;
    }
}
