<?php

namespace Auth\Application\EventSubscriber;

use Auth\Domain\Repository\OAuthClientRepositoryInterface;
use Shared\Event\ApplicationSetupEvent;
use Shared\Search\SearchClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OAuthClientSetupSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
        private OAuthClientRepositoryInterface $oAuthClientRepository,
    ) {
    }

    public function onSetup(): void
    {
        $this->searchClient->createIndex('oauth_clients', [
            'name' => ['type' => 'text'],
            'public_key' => ['type' => 'keyword'],
            'secret_key' => ['type' => 'keyword'],
            'allowed_grant_types' => ['type' => 'text'],
            'redirect_urls' => ['type' => 'text'],
        ]);

        foreach ($this->oAuthClientRepository->findAll() as $oAuthClient) {
            $this->searchClient->createDocument('oauth_clients', $oAuthClient->getId(), [
                'name' => $oAuthClient->getName(),
                'public_key' => $oAuthClient->getPublicKey(),
                'secret_key' => $oAuthClient->getSecretKey(),
                'allowed_grant_types' => $oAuthClient->getAllowedGrantTypes(),
                'redirect_urls' => $oAuthClient->getRedirectUrls(),
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
