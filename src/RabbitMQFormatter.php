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
        $host = php_uname('n');
        $requestType = (strpos(php_sapi_name(), 'cli') !== false) ? 'cmd' : 'http';
        $record['extra'] += [
            'host' => $host,
            'queryString' => request()->server('REQUEST_URI'),
            'requestType' => $requestType,
            'fields' => [
                'log_name' => $this->logName,
                'hash' => $this->hash,
                'tag' => 'laravel',
            ],
        ];
        $normalized = parent::normalizeRecord($record);

        if (isset($normalized['context']) && $normalized['context'] === []) {
            if ($this->ignoreEmptyContextAndExtra) {
                unset($normalized['context']);
            } else {
                $normalized['context'] = new \stdClass();
            }
        }
        if (isset($normalized['context'])) {
            //normal context for elk search
            $normalized['context'] = ['json' => json_encode($normalized['context'])];
        }

        if (isset($normalized['extra']) && $normalized['extra'] === []) {
            if ($this->ignoreEmptyContextAndExtra) {
                unset($normalized['extra']);
            } else {
                $normalized['extra'] = new \stdClass();
            }
        }

        return $this->toJson($normalized, true) . ($this->appendNewline ? "\n" : '');
    }
}
