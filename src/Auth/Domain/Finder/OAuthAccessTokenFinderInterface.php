<?php

namespace Auth\Domain\Finder;

use Auth\Domain\View\OAuthAccessTokenView;
use Symfony\Component\Uid\Ulid;

interface OAuthAccessTokenFinderInterface
{
    public function findById(Ulid $id): ?OAuthAccessTokenView;
    public function findByToken(string $token): ?OAuthAccessTokenView;
}
