<?php

namespace Auth\Application\Dto;

final readonly class AuthorizeOAuthClientDto
{
    public function __construct(
        private string $publicKey,
        private string $grantType,
        private string $redirectUrl,
    ) {
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
