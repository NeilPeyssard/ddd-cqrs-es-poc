<?php

namespace Auth\Domain\Command;

use Symfony\Component\Uid\Ulid;

final readonly class FindOAuthAccessToken
{
    public function __construct(
        private Ulid $id,
    ) {
    }

    public function getId(): Ulid
    {
        return $this->id;
    }
}
