<?php

namespace Shared\Bus;

use Shared\Event\DomainEventInterface;

interface EventBusInterface
{
    public function dispatch(DomainEventInterface $message): void;
}
