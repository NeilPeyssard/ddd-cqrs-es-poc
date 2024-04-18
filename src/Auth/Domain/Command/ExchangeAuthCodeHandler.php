<?php

namespace Auth\Domain\Command;

use Auth\Domain\Finder\OAuthClientFinderInterface;
use Auth\Domain\Finder\OAuthCodeFinderInterface;
use Auth\Domain\OAuthAccessToken;
use Shared\Bus\EventBusInterface;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final readonly class ExchangeAuthCodeHandler
{
    public function __construct(
        private EventBusInterface $eventBus,
        private OAuthClientFinderInterface $oAuthClientFinder,
        private OAuthCodeFinderInterface $oAuthCodeFinder,
    ) {
    }

    public function __invoke(ExchangeAuthCode $command): void
    {
        $client = $this->oAuthClientFinder->findByPublicKey($command->getClientPublicKey());

        if (!$client || $client->secretKey !== $command->getClientSecretKey()) {
            throw new \Exception('Invalid client');
        }

        $authCodeView = $this->oAuthCodeFinder->findByAuthCode($command->getAuthCode());

        if (!$authCodeView) {
            throw new \Exception('Invalid authorization code');
        }

        $accessToken = OAuthAccessToken::create(
            $command->getOAuthAccessTokenId(),
            new Ulid($client->id),
            new Ulid($authCodeView->userId),
            $authCodeView->expiresAt,
            new Uuid($authCodeView->token),
        );

        foreach ($accessToken->getEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
