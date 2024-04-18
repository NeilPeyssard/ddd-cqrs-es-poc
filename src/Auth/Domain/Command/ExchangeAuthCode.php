<?php

namespace Auth\Domain\Command;

use Symfony\Component\Uid\Ulid;

final readonly class ExchangeAuthCode
{
    public function __construct(
        private Ulid $oAuthAccessTokenId,
        private string $clientPublicKey,
        private string $clientSecretKey,
        private string $authCode,
    ) {
    }

    public function getOAuthAccessTokenId(): Ulid
    {
        return $this->oAuthAccessTokenId;
    }

    public function getClientPublicKey(): string
    {
        return $this->clientPublicKey;
    }

    public function getClientSecretKey(): string
    {
        return $this->clientSecretKey;
    }

    public function getAuthCode(): string
    {
        return $this->authCode;
    }
}
