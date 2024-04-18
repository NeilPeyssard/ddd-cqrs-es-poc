<?php

namespace Auth\Domain\Event;

use Auth\Domain\OAuthClient;
use Shared\Event\DomainEventInterface;
use Symfony\Component\Uid\Ulid;

final readonly class CreateOAuthClientEvent implements DomainEventInterface
{
    public function __construct(
        private Ulid $id,
        private string $publicKey,
        private string $secretKey,
        private string $name,
        private array $allowedGrantTypes,
        private array $redirectUrls
    ) {
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAllowedGrantTypes(): array
    {
        return $this->allowedGrantTypes;
    }

    public function getRedirectUrls(): array
    {
        return $this->redirectUrls;
    }

    public function getAggregateType(): string
    {
        return OAuthClient::class;
    }
}
