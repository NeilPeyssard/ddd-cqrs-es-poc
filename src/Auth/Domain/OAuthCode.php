<?php

namespace Auth\Domain;

use Auth\Domain\Event\CreateOAuthCodeEvent;
use Shared\Event\EventCapability;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class OAuthCode
{
    use EventCapability;

    private ?Ulid $id = null;
    private ?Ulid $clientId = null;
    private ?Ulid $userId = null;
    private ?\DateTimeImmutable $expiresAt = null;
    private ?\DateTimeImmutable $consumedAt = null;
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
    ): OAuthCode {
        $oAuthCode = new self();

        $oAuthCode->id = $id;
        $oAuthCode->clientId = $clientId;
        $oAuthCode->userId = $userId;
        $oAuthCode->expiresAt = $expiresAt;
        $oAuthCode->token = $token;

        $oAuthCode->addEvent(new CreateOAuthCodeEvent(
            $oAuthCode->id,
            $oAuthCode->clientId,
            $oAuthCode->userId,
            $oAuthCode->expiresAt,
            $oAuthCode->token,
        ));

        return $oAuthCode;
    }

    public static function fromEvents(array $events): OAuthCode
    {
        $oAuthCode = new self();

        foreach ($events as $event) {
            if ($event instanceof CreateOAuthCodeEvent) {
                $oAuthCode->id = $event->getId();
                $oAuthCode->clientId = $event->getClientId();
                $oAuthCode->userId = $event->getUserId();
                $oAuthCode->expiresAt = $event->getExpiresAt();
                $oAuthCode->token = $event->getToken();
            }
        }

        return $oAuthCode;
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

    public function getConsumedAt(): ?\DateTimeImmutable
    {
        return $this->consumedAt;
    }

    public function getToken(): ?Uuid
    {
        return $this->token;
    }
}
