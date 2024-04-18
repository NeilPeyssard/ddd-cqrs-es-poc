<?php

namespace Auth\Domain\View;

final class OAuthAccessTokenView
{
    public ?string $accessToken;
    public ?\DateTimeImmutable $expiresAt;
    public string $userId;

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= time();
    }
}
