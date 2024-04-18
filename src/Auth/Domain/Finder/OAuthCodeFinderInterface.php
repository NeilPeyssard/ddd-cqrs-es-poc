<?php

namespace Auth\Domain\Finder;

use Auth\Domain\View\OAuthCodeView;

interface OAuthCodeFinderInterface
{
    public function findByAuthCode(string $authCode): ?OAuthCodeView;
}
