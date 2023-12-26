<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $config = $this->app->make('config');

        $config->set('queue.connections', array_merge(
            [
                'sns-sqs' => [
                    'driver' => 'sns-sqs',
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    'account_id' => env('AWS_ACCOUNT_ID', '000000000000'),
                    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
                    'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
                    'queue' => env('SQS_QUEUE', 'default'),
                    'suffix' => env('SQS_SUFFIX'),
                    'sns_endpoint' => env('SNS_ENDPOINT'),
                    'sns_topic_arn' => env('SNS_TOPIC_ARN', 'arn:aws:sns:us-east-1:your-account-id:topic'),
                    'after_commit' => false,
                ],
            ],
            $config->get('queue.connections', [])
        ));

        //$config->set('queue.default', 'sns-sqs');
    }
}
