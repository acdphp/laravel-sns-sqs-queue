<?php

namespace Acdphp\SnsSqsQueue\Sns;

use Aws\Sns\SnsClient as AwsSnsClient;

class SnsClient extends AwsSnsClient
{
    public function __construct(array $args)
    {
        $args['endpoint'] = $this->getSnsEndpoint($args);

        parent::__construct($args);
    }

    protected function getSnsEndpoint(array $args): string
    {
        if (empty($args['sns_endpoint'])) {
            return sprintf('https://sns.%s.amazonaws.com', $args['region']);
        }

        return $args['sns_endpoint'];
    }
}
