<?php

namespace Acdphp\SnsSqsQueue\Queue\Connectors;

use Acdphp\SnsSqsQueue\Queue\SnsSqsQueue;
use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;
use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Support\Arr;

class SnsSqsConnector extends SqsConnector
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
        }

        return new SnsSqsQueue(
            new SnsClient($config),
            $config['sns_topic_arn'],
            new SqsClient($config),
            $config['queue'],
            $config['prefix'] ?? '',
            $config['suffix'] ?? '',
            $config['after_commit'] ?? null
        );
    }
}
