<?php

namespace Project\Domain\Command;

use Symfony\Component\Uid\Ulid;

final readonly class CreateProject
{
    public function __construct(
        private Ulid $id,
        private string $name,
    ) {
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
