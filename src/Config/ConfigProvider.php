<?php

declare(strict_types=1);

namespace Antidot\Runtime\Config;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

final class ConfigProvider
{
    /**
     * @return array{services: array<string, string>}
     */
    public function __invoke(): array
    {
        return [
            'services' => [
                SapiEmitter::class => SapiEmitter::class,
            ]
        ];
    }
}
