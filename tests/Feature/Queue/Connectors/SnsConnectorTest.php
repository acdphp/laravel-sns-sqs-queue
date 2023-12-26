<?php

use Acdphp\SnsSqsQueue\Queue\Connectors\SnsConnector;
use Acdphp\SnsSqsQueue\Queue\SnsSqsQueue;

test('can instantiate sns connector', function () {
    expect(new SnsConnector())
        ->toBeInstanceOf(SnsConnector::class);
});

test('can connect to sns connector', function () {
    expect((new SnsConnector())->connect(config('queue.connections.sns-sqs')))
        ->toBeInstanceOf(SnsSqsQueue::class);
});
