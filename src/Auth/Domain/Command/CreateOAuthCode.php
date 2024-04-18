<?php

namespace Auth\Domain\Command;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final readonly class CreateOAuthCode
{
    public function __construct(
        private Ulid $id,
        private Ulid $clientId,
        private ?Ulid $userId,
        private \DateTimeImmutable $expiresAt,
        private Uuid $token,
    ) {
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getClientId(): Ulid
    {
        return $this->clientId;
    }

    public function getUserId(): ?Ulid
    {
        return $this->userId;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getToken(): Uuid
    {
        return $this->token;
    }
}
