<?php

namespace Shared\Persistence;

use Shared\Event\DomainEventInterface;
use Symfony\Component\Uid\Ulid;

interface EventStoreInterface
{
    public function findByAggregateType(string $type): array;

    public function findById(Ulid $id): array;

    public function add(DomainEventInterface $event): void;
}
