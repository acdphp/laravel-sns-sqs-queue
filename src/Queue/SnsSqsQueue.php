<?php

namespace Acdphp\SnsSqsQueue\Queue;

use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;
use Illuminate\Queue\SqsQueue;

class SnsSqsQueue extends SqsQueue
{
    protected SnsClient $sns;

    protected string $topicArn;

    public function __construct(
        SnsClient $sns,
        string $topicArn,
        SqsClient $sqs,
        $default,
        $prefix = '',
        $suffix = '',
        $dispatchAfterCommit = false
    ) {
        $this->sns = $sns;
        $this->topicArn = $topicArn;

        parent::__construct($sqs, $default, $prefix, $suffix, $dispatchAfterCommit);
    }

    /**
     * {@inheritdoc}
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return $this->sns->publish([
            'TopicArn' => $this->topicArn,
            'Message' => $payload,
        ])->get('MessageId');
    }

    /**
     * {@inheritdoc}
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->enqueueUsing(
            $job,
            $this->createPayload($job, $queue ?: $this->default, $data),
            $queue,
            $delay,
            function ($payload, $queue, $delay) {
                return $this->sns->publish([
                    'TopicArn' => $this->topicArn,
                    'MessageBody' => $payload,
                    'DelaySeconds' => $this->secondsUntil($delay),
                ])->get('MessageId');
            }
        );
    }
}
