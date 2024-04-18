<?php

namespace Project\Domain\Command;

use Symfony\Component\Uid\Ulid;

final readonly class ReadProject
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
