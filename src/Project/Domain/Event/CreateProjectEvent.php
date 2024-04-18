<?php

namespace Project\Domain\Event;

use Project\Domain\Project;
use Shared\Event\DomainEventInterface;
use Symfony\Component\Uid\Ulid;

final readonly class CreateProjectEvent implements DomainEventInterface
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

    public function getAggregateType(): string
    {
        return Project::class;
    }
}
