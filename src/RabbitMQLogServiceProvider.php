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
        $this->publishes([
            __DIR__ . '/../config/rabbit_mq_logger.php' => config_path('rabbit_mq_logger.php'),
        ]);
    }
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/rabbit_mq_logger.php',
            'rabbit_mq_logger'
        );
    }
}
