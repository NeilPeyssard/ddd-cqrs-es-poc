<?php

namespace Auth\Infrastructure\Projector;

use Auth\Domain\Event\CreateOAuthAccessTokenEvent;
use Shared\Search\SearchClientInterface;

final readonly class OAuthAccessTokenProjector
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function create(CreateOAuthAccessTokenEvent $event): void
    {
        $this->searchClient->createDocument('oauth_access_tokens', $event->getId(), [
            'token' => $event->getToken(),
            'client_id' => $event->getClientId(),
            'user_id' => $event->getUserId(),
            'expires_at' => $event->getExpiresAt()->format(\DateTimeInterface::ATOM),
        ]);
    }
}
