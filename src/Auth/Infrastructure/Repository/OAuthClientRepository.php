<?php

namespace Auth\Infrastructure\Repository;

use Auth\Domain\OAuthClient;
use Auth\Domain\Repository\OAuthClientRepositoryInterface;
use Shared\Persistence\EventStoreInterface;

final readonly class OAuthClientRepository implements OAuthClientRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function findAll(): array
    {
        $eventsByClient = $clients = [];

        foreach ($this->eventStore->findByAggregateType(OAuthClient::class) as $event) {
            if (!isset($eventsByClient[$event->getId()->__toString()])) {
                $eventsByClient[$event->getId()->__toString()] = [];
            }

            $eventsByClient[$event->getId()->__toString()][] = $event;
        }

        foreach ($eventsByClient as $clientEvents) {
            $clients[] = OAuthClient::fromEvents($clientEvents);
        }

        return $clients;
    }

    public function save(OAuthClient $client): void
    {
        foreach ($client->getEvents() as $event) {
            $this->eventStore->add($event);
        }
    }
}
