<?php

namespace Auth\Domain\Repository;

use Auth\Domain\OAuthCode;

interface OAuthCodeRepositoryInterface
{
    public function findAll(): array;
    public function save(OAuthCode $authCode): void;
}
