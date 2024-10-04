<?php

return [
    'demo' => [
        'name' => 'rabbit_mq_demo',
        'level' => 'debug',
        'host' => 'localhost',
        'port' => '5672',
        'username' => null,
        'password' => null,
        'exchange' => 'exchange',
        'routing_key' => 'routing_key',
        'queue' => 'demo_queue',
        'bubble' => true,
        'include_stack_traces' => true,
    ],
    'rabbit_mq' => [
        'name' => env('RABBIT_MQ_LOG_NAME', 'rabbit_mq'),
        'level' => env('RABBIT_MQ_LOG_LEVEL', 'error'),
        'host' => env('RABBIT_MQ_LOG_HOST', 'localhost'),
        'port' => env('RABBIT_MQ_LOG_PORT', '5672'),
        'username' => env('RABBIT_MQ_LOG_USERNAME'),
        'password' => env('RABBIT_MQ_LOG_PASSWORD'),
        'exchange' => env('RABBIT_MQ_LOG_EXCHANGE', 'laravel_logs'),
        'routing_key' => env('RABBIT_MQ_LOG_ROUTING_KEY', 'laravel_log'),
        'queue' => env('RABBIT_MQ_LOG_QUEUE', 'laravel_log_queue'),
        'bubble' => env('RABBIT_MQ_LOG_BUBBLE', true),
        'include_stack_traces' => env('APP_DEBUG', false)
    ]
];
