<?php

namespace gan068\Logging;

use gan068\Logging\RabbitMQHandler;
use Monolog\Logger;
use Illuminate\Support\Str;

class RabbitMQLogger
{
    public function __invoke(array $config)
    {
        $handler = new RabbitMQHandler(
            $config
        );
        $hash = Str::uuid()->toString();
        $formatter = new RabbitMQFormatter($config['name']);
        $formatter->setHash($hash);
        $formatter->setLogName($config['name']);
        if ($config['with']['include_stack_traces'] === true) {
            $formatter->includeStacktraces(true);
        }
        $handler->setFormatter($formatter);
        return new Logger($config['name'], [$handler]);
    }
}
