<?php

use Acdphp\SnsSqsQueue\Queue\Connectors\SnsSqsConnector;
use Acdphp\SnsSqsQueue\Queue\SnsSqsQueue;

test('can instantiate sns-sqs connector', function () {
    expect(new SnsSqsConnector())
        ->toBeInstanceOf(SnsSqsConnector::class);
});

test('can connect to sns-sqs connector', function () {
    expect((new SnsSqsConnector())->connect(config('queue.connections.sns-sqs')))
        ->toBeInstanceOf(SnsSqsQueue::class);
});
