<?php

namespace Auth\Domain\Repository;

use Auth\Domain\OAuthAccessToken;

interface OAuthAccessTokenRepositoryInterface
{
    public function findAll(): array;

    public function save(OAuthAccessToken $accessToken): void;
}
