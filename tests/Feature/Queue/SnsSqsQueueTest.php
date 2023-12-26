<?php

use Acdphp\SnsSqsQueue\Queue\SnsSqsQueue;
use Aws\Result;
use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\SqsJob;
use PHPUnit\Framework\MockObject\MockObject;
use Workbench\App\Jobs\MicroserviceMessageJob;

beforeEach(function () {
    /**
     * @var MockObject|SnsClient $this->snsClient
     */
    $this->snsClient = $this->getMockBuilder(SnsClient::class)
        ->disableOriginalConstructor()
        ->addMethods(['publish'])
        ->getMock();

    /**
     * @var MockObject|SqsClient $this->sqsClient
     */
    $this->sqsClient = $this->getMockBuilder(SqsClient::class)
        ->disableOriginalConstructor()
        ->addMethods(['receiveMessage'])
        ->getMock();

    $this->queue = new SnsSqsQueue(
        $this->snsClient,
        'arn:aws:sns:us-east-1:your-account-id:topic',
        $this->sqsClient,
        'default_queue'
    );

    $this->queue->setContainer($this->createMock(Container::class));
});

test('can instantiate queue', function () {
    expect($this->queue)
        ->toBeInstanceOf(SnsSqsQueue::class);
});

test('push will publish to sns with proper payload', function () {
    $job = new MicroserviceMessageJob(['data' => 'test']);

    $this->snsClient->expects($this->once())
        ->method('publish')
        ->with($this->callback(
            function ($payload) use ($job) {
                $data = json_decode($payload['Message'], true, 512)['data'];

                return
                    $payload['TopicArn'] === config('queue.connections.sns-sqs.sns_topic_arn') &&
                    $data['commandName'] === MicroserviceMessageJob::class &&
                    $data['command'] === serialize($job);
            }
        ))
        ->willReturn(new Result([]));

    $this->queue->push($job);
});

test('pop will receive message from sqs', function () {
    $this->sqsClient->expects($this->once())
        ->method('receiveMessage')
        ->willReturn([
            'Messages' => [['test job']],
        ]);

    expect($this->queue->pop())
        ->toBeInstanceOf(SqsJob::class);
});
