<?php

namespace Auth\Domain;

use Auth\Domain\Event\CreateOAuthAccessTokenEvent;
use Shared\Event\EventCapability;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class OAuthAccessToken
{
    use EventCapability;

    private ?Ulid $id = null;
    private ?Ulid $clientId = null;
    private ?Ulid $userId = null;
    private ?\DateTimeImmutable $expiresAt = null;
    private ?Uuid $token = null;

    private function __construct()
    {
    }

    public static function create(
        Ulid $id,
        Ulid $clientId,
        ?Ulid $userId,
        \DateTimeImmutable $expiresAt,
        Uuid $token,
    ): OAuthAccessToken {
        $oAuthAccessToken = new self();

        $oAuthAccessToken->id = $id;
        $oAuthAccessToken->clientId = $clientId;
        $oAuthAccessToken->userId = $userId;
        $oAuthAccessToken->expiresAt = $expiresAt;
        $oAuthAccessToken->token = $token;

        $oAuthAccessToken->addEvent(new CreateOAuthAccessTokenEvent(
            $oAuthAccessToken->id,
            $oAuthAccessToken->clientId,
            $oAuthAccessToken->userId,
            $oAuthAccessToken->expiresAt,
            $oAuthAccessToken->token,
        ));

        return $oAuthAccessToken;
    }

    public static function fromEvents(array $events): OAuthAccessToken
    {
        $oAuthAccessToken = new self();

        foreach ($events as $event) {
            if ($event instanceof CreateOAuthAccessTokenEvent) {
                $oAuthAccessToken->id = $event->getId();
                $oAuthAccessToken->clientId = $event->getClientId();
                $oAuthAccessToken->userId = $event->getUserId();
                $oAuthAccessToken->expiresAt = $event->getExpiresAt();
                $oAuthAccessToken->token = $event->getToken();
            }
        }

        return $oAuthAccessToken;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getClientId(): ?Ulid
    {
        return $this->clientId;
    }

    public function getUserId(): ?Ulid
    {
        return $this->userId;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getToken(): ?Uuid
    {
        return $this->token;
    }
}
