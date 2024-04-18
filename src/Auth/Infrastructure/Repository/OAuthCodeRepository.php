<?php

namespace Auth\Infrastructure\Repository;

use Auth\Domain\OAuthCode;
use Auth\Domain\Repository\OAuthCodeRepositoryInterface;
use Shared\Persistence\EventStoreInterface;

final readonly class OAuthCodeRepository implements OAuthCodeRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function findAll(): array
    {
        $eventsByAuthCode = $authCodes = [];

        foreach ($this->eventStore->findByAggregateType(OAuthCode::class) as $event) {
            if (!isset($eventsByAuthCode[$event->getId()->__toString()])) {
                $eventsByAuthCode[$event->getId()->__toString()] = [];
            }

            $eventsByAuthCode[$event->getId()->__toString()][] = $event;
        }

        foreach ($eventsByAuthCode as $authCodeEvents) {
            $authCodes[] = OAuthCode::fromEvents($authCodeEvents);
        }

        return $authCodes;
    }

    public function save(OAuthCode $authCode): void
    {
        foreach ($authCode->getEvents() as $event) {
            $this->eventStore->add($event);
        }
    }
}
