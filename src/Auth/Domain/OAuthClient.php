<?php

namespace Auth\Domain;

use Auth\Domain\Event\CreateOAuthClientEvent;
use Shared\Event\EventCapability;
use Symfony\Component\Uid\Ulid;

final class OAuthClient
{
    use EventCapability;

    public const GRANT_TYPE_AUTH_CODE = 'auth_code';

    private ?Ulid $id = null;
    private ?string $publicKey = null;
    private ?string $secretKey = null;
    private ?string $name = null;
    private array $allowedGrantTypes = [];
    private array $redirectUrls = [];

    private function __construct()
    {
    }

    public static function create(
        Ulid $id,
        string $publicKey,
        string $secretKey,
        string $name,
        array $allowedGrantTypes,
        array $redirectUrls
    ): OAuthClient {
        $client = new self();

        $client->id = $id;
        $client->publicKey = $publicKey;
        $client->secretKey = $secretKey;
        $client->name = $name;
        $client->allowedGrantTypes = $allowedGrantTypes;
        $client->redirectUrls = $redirectUrls;

        $client->addEvent(new CreateOAuthClientEvent(
            $client->id,
            $client->publicKey,
            $client->secretKey,
            $client->name,
            $client->allowedGrantTypes,
            $client->redirectUrls,
        ));

        return $client;
    }

    public static function fromEvents(array $events): OAuthClient
    {
        $client = new self();

        foreach ($events as $event) {
            if ($event instanceof CreateOAuthClientEvent) {
                $client->id = $event->getId();
                $client->publicKey = $event->getPublicKey();
                $client->secretKey = $event->getSecretKey();
                $client->name = $event->getName();
                $client->allowedGrantTypes = $event->getAllowedGrantTypes();
                $client->redirectUrls = $event->getRedirectUrls();
            }
        }

        return $client;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function getName(): ?string
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
}
