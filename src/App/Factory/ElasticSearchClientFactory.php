<?php

namespace App\Factory;

use Elastica\Client;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ElasticSearchClientFactory
{
    public function __construct(
        #[Autowire(env: 'ES_SERVER_HOST')]
        private readonly string $host,
        #[Autowire(env: 'ES_SERVER_PORT')]
        private readonly string $port,
    ) {
    }

    public function createClient(): Client
    {
        return new Client([
            'host' => $this->host,
            'port' => $this->port,
        ]);
    }
}
