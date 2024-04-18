<?php

namespace Auth\Domain\View;

final class OAuthClientView
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $secretKey = null;
    public array $allowedGrantTypes = [];
    public array $redirectUrls = [];
}
