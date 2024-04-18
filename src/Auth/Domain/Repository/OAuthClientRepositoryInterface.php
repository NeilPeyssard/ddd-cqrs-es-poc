<?php

namespace Auth\Domain\Repository;

use Auth\Domain\OAuthClient;

interface OAuthClientRepositoryInterface
{
    public function findAll(): array;

    public function save(OAuthClient $client): void;
}
