<?php

namespace Auth\Domain\Command;

final readonly class FindOAuthAccessTokenByToken
{
    public function __construct(
        private string $token,
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
