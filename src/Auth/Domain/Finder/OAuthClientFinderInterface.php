<?php

namespace Auth\Domain\Finder;

use Auth\Domain\View\OAuthClientView;

interface OAuthClientFinderInterface
{
    public function findByPublicKey(string $publicKey): ?OAuthClientView;
}
