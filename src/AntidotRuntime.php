<?php

declare(strict_types=1);

namespace Antidot\Runtime;

use Psr\Container\ContainerInterface;
use Symfony\Component\Runtime\GenericRuntime;
use Symfony\Component\Runtime\RunnerInterface;

final class AntidotRuntime extends GenericRuntime
{
    public function getRunner(?object $application): RunnerInterface
    {
        if ($application instanceof ContainerInterface) {
            return new AntidotRunner($application);
        }

        return parent::getRunner($application);
    }
}
