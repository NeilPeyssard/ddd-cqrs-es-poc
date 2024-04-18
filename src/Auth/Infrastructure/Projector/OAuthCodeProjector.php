<?php

namespace Auth\Infrastructure\Projector;

use Auth\Domain\Event\CreateOAuthCodeEvent;
use Shared\Search\SearchClientInterface;

final readonly class OAuthCodeProjector
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function create(CreateOAuthCodeEvent $event): void
    {
        $this->searchClient->createDocument('oauth_codes', $event->getId(), [
            'token' => $event->getToken(),
            'client_id' => $event->getClientId(),
            'user_id' => $event->getUserId(),
            'expires_at' => $event->getExpiresAt()->format(\DateTimeInterface::ATOM),
        ]);
    }
}
