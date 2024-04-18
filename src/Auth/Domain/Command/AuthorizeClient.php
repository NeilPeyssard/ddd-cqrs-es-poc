<?php

namespace Auth\Domain\Command;

final readonly class AuthorizeClient
{
    public function __construct(
        private string $publicKey,
    ) {
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
