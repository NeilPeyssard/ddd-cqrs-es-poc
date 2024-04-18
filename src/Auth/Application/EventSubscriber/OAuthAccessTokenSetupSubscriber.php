<?php

namespace Auth\Application\EventSubscriber;

use Auth\Domain\Repository\OAuthAccessTokenRepositoryInterface;
use Shared\Event\ApplicationSetupEvent;
use Shared\Search\SearchClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OAuthAccessTokenSetupSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
        private OAuthAccessTokenRepositoryInterface $oAuthAccessTokenRepository,
    ) {
    }

    public function onSetup(): void
    {
        $this->searchClient->createIndex('oauth_access_tokens', [
            'token' => ['type' => 'keyword'],
            'client_id' => ['type' => 'keyword'],
            'user_id' => ['type' => 'keyword'],
            'expires_at' => ['type' => 'date', 'format' => 'date_time_no_millis'],
        ]);

        foreach ($this->oAuthAccessTokenRepository->findAll() as $oAuthAccessToken) {
            $this->searchClient->createDocument('oauth_access_tokens', $oAuthAccessToken->getId(), [
                'token' => $oAuthAccessToken->getToken(),
                'client_id' => $oAuthAccessToken->getClientId(),
                'user_id' => $oAuthAccessToken->getUserId(),
                'expires_at' => $oAuthAccessToken->getExpiresAt()?->format(\DateTimeInterface::ATOM),
            ]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationSetupEvent::class => 'onSetup',
        ];
    }
}
