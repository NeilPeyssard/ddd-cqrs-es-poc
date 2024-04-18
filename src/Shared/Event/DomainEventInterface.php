<?php

namespace Shared\Event;

use Symfony\Component\Uid\Ulid;

interface DomainEventInterface
{
    public function getId(): Ulid;

    public function getAggregateType(): string;
}
