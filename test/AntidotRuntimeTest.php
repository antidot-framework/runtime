<?php

declare(strict_types=1);

namespace Antidot\Test\Runtime;

use Psr\Container\ContainerInterface;
use Antidot\Runtime\AntidotRunner;
use Antidot\Runtime\AntidotRuntime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Runtime\Runner\ClosureRunner;

final class AntidotRuntimeTest extends TestCase
{
    public function testItShouldGetAntidotRunner(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $runtime = new AntidotRuntime();
        $runner = $runtime->getRunner($container);
        self::assertInstanceOf(AntidotRunner::class, $runner);
    }

    public function testItShouldGetDefoultRunner(): void
    {
        $runtime = new AntidotRuntime();
        $runner = $runtime->getRunner(null);
        self::assertInstanceOf(ClosureRunner::class, $runner);
    }
}
