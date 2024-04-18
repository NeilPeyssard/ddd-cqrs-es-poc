<?php

namespace Auth\Infrastructure\Repository;

use Auth\Domain\OAuthAccessToken;
use Auth\Domain\Repository\OAuthAccessTokenRepositoryInterface;
use Auth\Domain\Repository\OAuthCodeRepositoryInterface;
use Shared\Persistence\EventStoreInterface;

final readonly class OAuthAccessTokenRepository implements OAuthAccessTokenRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function findAll(): array
    {
        $eventsByAccessToken = $accessTokens = [];

        foreach ($this->eventStore->findByAggregateType(OAuthAccessToken::class) as $event) {
            if (!isset($eventsByAccessToken[$event->getId()->__toString()])) {
                $eventsByAccessToken[$event->getId()->__toString()] = [];
            }

            $eventsByAccessToken[$event->getId()->__toString()][] = $event;
        }

        foreach ($eventsByAccessToken as $accessTokensEvents) {
            $accessTokens[] = OAuthAccessToken::fromEvents($accessTokensEvents);
        }

        return $accessTokens;
    }

    public function save(OAuthAccessToken $accessToken): void
    {
        foreach ($accessToken->getEvents() as $event) {
            $this->eventStore->add($event);
        }
    }
}
