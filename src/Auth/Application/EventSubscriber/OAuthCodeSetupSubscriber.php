<?php

namespace Auth\Application\EventSubscriber;

use Auth\Domain\Repository\OAuthCodeRepositoryInterface;
use Shared\Event\ApplicationSetupEvent;
use Shared\Search\SearchClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OAuthCodeSetupSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
        private OAuthCodeRepositoryInterface $oAuthCodeRepository,
    ) {
    }

    public function onSetup(): void
    {
        $this->searchClient->createIndex('oauth_codes', [
            'token' => ['type' => 'keyword'],
            'client_id' => ['type' => 'keyword'],
            'user_id' => ['type' => 'keyword'],
            'expires_at' => ['type' => 'date', 'format' => 'date_time_no_millis'],
            'consumed_at' => ['type' => 'date', 'format' => 'date_time_no_millis'],
        ]);

        foreach ($this->oAuthCodeRepository->findAll() as $oAuthCode) {
            $this->searchClient->createDocument('oauth_codes', $oAuthCode->getId(), [
                'token' => $oAuthCode->getToken(),
                'client_id' => $oAuthCode->getClientId(),
                'user_id' => $oAuthCode->getUserId(),
                'expires_at' => $oAuthCode->getExpiresAt()?->format(\DateTimeInterface::ATOM),
                'consumed_at' => $oAuthCode->getConsumedAt()?->format(\DateTimeInterface::ATOM),
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
