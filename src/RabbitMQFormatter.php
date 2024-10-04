<?php

declare(strict_types=1);

namespace gan068\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class RabbitMQFormatter extends JsonFormatter
{
    protected string $hash;
    protected string $logName;

    public function setHash(string $hash)
    {
        $this->hash = $hash;
    }

    public function setLogName(string $logName)
    {
        $this->logName = $logName;
    }

    /**
     * {@inheritdoc}
     */
    public function format(LogRecord $record): string
    {
        $fixed_record = $this->normalize($record);

        //normal context for elk search
        if (isset($fixed_record['context'])) {
            if (!isset($fixed_record['context']['exception'])) {
                $fixed_record['context'] = ['json' => json_encode($fixed_record['context'])];
            }
        }
        $host = php_uname('n');
        $requestType = (strpos(php_sapi_name(), 'cli') !== false) ? 'cmd' : 'http';
        $fixed_record += [
            'host' => $host,
            'queryString' => request()->server('REQUEST_URI'),
            'requestType' => $requestType,
            'fields' => [
                'log_name' => $this->logName,
                'hash' => $this->hash,
                'tag' => 'laravel',
                'type' => "laravel-log-{$this->logName}",
            ],
        ];
        return $this->toJson($fixed_record, true) . ($this->appendNewline ? "\n" : '');
    }
}
