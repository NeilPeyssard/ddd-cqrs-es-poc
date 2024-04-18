<?php

namespace Auth\Domain\View;

final class OAuthCodeView
{
    public ?string $id = null;
    public ?string $userId = null;
    public ?\DateTimeImmutable $expiresAt = null;
    public ?string $token = null;
}
