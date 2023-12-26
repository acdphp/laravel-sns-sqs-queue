<?php

namespace Acdphp\SnsSqsQueue;

use Acdphp\SnsSqsQueue\Queue\Connectors\SnsConnector;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class SnsSqsQueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->afterResolving(QueueManager::class, function (QueueManager $manager) {
            $manager->addConnector('sns-sqs', function () {
                return new SnsConnector();
            });
        });
    }

    public function boot(): void
    {
    }
}
