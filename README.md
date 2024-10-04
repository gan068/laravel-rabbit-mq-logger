# Log to RabbitMQ

- [Log to RabbitMQ](#log-to-rabbitmq)
  - [Requirements](#requirements)
    - [install](#install)
    - [publish vendor](#publish-vendor)
    - [log settings](#log-settings)
    - [edit `.env`](#edit-env)
    - [add more Kafka topic](#add-more-kafka-topic)
  - [usage](#usage)

## Requirements

- Laravel 10.x~

### install

```bash
composer require gan068/laravel-rabbit-mq-logger "~1.0"
```

### publish vendor

```bash
php artisan vendor:publish --provider="gan068\Logging\RabbitMQLogServiceProvider"
```

### log settings

modify `config/logging.php` add

```php
    'channels' => [
        ...
        # for demo
        'rabbit_mq_demo' => [
            'driver' => 'custom',
            'via' => \gan068\Logging\RabbitMQLogger::class,
            'with' => config('rabbit_mq_logger.demo'),
        ],
        # for real usage
        'rabbit_mq' => [
            'driver' => 'custom',
            'via' => \gan068\Logging\RabbitMQLogger::class,
            'with' => config('rabbit_mq_logger.rabbit_mq'),
        ],
        ...
    ]
```

### edit `.env`

```bash
#sample

LOG_CHANNEL=stack
LOG_STACK=rabbit_mq

RABBIT_MQ_LOG_NAME=laravel-rabbit-mq-logger
RABBIT_MQ_LOG_LEVEL=debug
RABBIT_MQ_LOG_HOST=127.0.0.1
RABBIT_MQ_LOG_PORT=5672
RABBIT_MQ_LOG_USERNAME=laravel
RABBIT_MQ_LOG_PASSWORD=12345678
RABBIT_MQ_LOG_EXCHANGE=laravel_logs
RABBIT_MQ_LOG_ROUTING_KEY=laravel_log
RABBIT_MQ_LOG_QUEUE=laravel_log_queue
RABBIT_MQ_LOG_BUBBLE=true
```

### add more Kafka topic

modify `config/logging.php` add

```php
    'channels' => [
        ...
        'new-rabbit-mq-connection' => [
            'driver' => 'custom',
            'via' => \gan068\RabbitMQLogger\KafkaLogger::class,
            'with' => config('rabbit_mq_logger.new_rabbit_mq'),
        ],
        ...
    ]
```

modify `config/rabbit_mq_logger.php` add

```php

    'new_rabbit_mq' => [
        'name' => env('NEW_RABBIT_MQ_LOG_NAME', 'rabbit_mq'),
        'level' => env('NEW_RABBIT_MQ_LOG_LEVEL', 'error'),
        'host' => env('NEW_RABBIT_MQ_LOG_HOST', 'localhost'),
        'port' => env('NEW_RABBIT_MQ_LOG_PORT', '5672'),
        'username' => env('NEW_RABBIT_MQ_LOG_USERNAME'),
        'password' => env('NEW_RABBIT_MQ_LOG_PASSWORD'),
        'exchange' => env('NEW_RABBIT_MQ_LOG_EXCHANGE', 'laravel_logs'),
        'routing_key' => env('NEW_RABBIT_MQ_LOG_ROUTING_KEY', 'laravel_log'),
        'queue' => env('NEW_RABBIT_MQ_LOG_QUEUE', 'laravel_log_queue'),
        'bubble' => env('NEW_RABBIT_MQ_LOG_BUBBLE', true),
        'include_stack_traces' => env('APP_DEBUG', false)
    ]
```

modify `.env.example` add

```text
NEW_RABBIT_MQ_LOG_NAME=laravel-NEW_rabbit-mq-logger
NEW_RABBIT_MQ_LOG_LEVEL=debug
NEW_RABBIT_MQ_LOG_HOST=127.0.0.1
NEW_RABBIT_MQ_LOG_PORT=5672
NEW_RABBIT_MQ_LOG_USERNAME=laravel
NEW_RABBIT_MQ_LOG_PASSWORD=12345678
NEW_RABBIT_MQ_LOG_EXCHANGE=laravel_logs
NEW_RABBIT_MQ_LOG_ROUTING_KEY=laravel_log
NEW_RABBIT_MQ_LOG_QUEUE=laravel_log_queue
NEW_RABBIT_MQ_LOG_BUBBLE=true

```

## usage

```php
Log::connection('new_rabbit_mq')
    ->info('test info', ['test' => 'context']);
```
