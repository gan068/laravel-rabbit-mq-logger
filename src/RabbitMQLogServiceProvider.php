<?php

namespace gan068\Logging;

use Illuminate\Support\ServiceProvider;

class RabbitMQLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath($raw = __DIR__ . '/../config/rabbit_mq_logger.php') ?: $raw;
        $this->mergeConfigFrom($source, 'rabbit_mq_logger');
    }
}
