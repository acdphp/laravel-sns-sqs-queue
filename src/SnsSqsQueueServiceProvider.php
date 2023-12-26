<?php

namespace Acdphp\SnsSqsQueue;

use Acdphp\SnsSqsQueue\Queue\Connectors\SnsSqsConnector;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class SnsSqsQueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->afterResolving(QueueManager::class, function (QueueManager $manager) {
            $manager->addConnector('sns-sqs', function () {
                return new SnsSqsConnector();
            });
        });
    }

    public function boot(): void
    {
    }
}
