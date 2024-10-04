<?php

namespace gan068\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQHandler extends AbstractProcessingHandler
{
    /**
     * @var AMQPStreamConnection
     */
    private AMQPStreamConnection $connection;
    /**
     * @var AbstractChannel|AMQPChannel
     */
    private $channel;
    /**
     * @var mixed|string
     */
    private $exchange;
    /**
     * @var mixed|string
     */
    private $routingKey;

    /**
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $level = $config['level'];
        $bubble = $config['bubble'];
        parent::__construct($level, $bubble);

        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password']
        );
        $this->channel = $this->connection->channel();
        $this->exchange = $config['exchange'];
        $this->routingKey = $config['routing_key'];
        $this->channel->exchange_declare($config['exchange'], 'direct', false, true, false);

        $queueName = $config['queue'];
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->channel->queue_bind($queueName, $this->exchange, $this->routingKey);
    }

    /**
     * @param LogRecord $record
     * @return void
     */
    public function write(LogRecord $record): void
    {
        $data = json_encode($record->toArray());
        $msg = new AMQPMessage($data, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, $this->exchange, $this->routingKey);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}
