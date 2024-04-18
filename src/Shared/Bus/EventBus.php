<?php

namespace Shared\Bus;

use Shared\Event\DomainEventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {
    }

    public function dispatch(DomainEventInterface $message): void
    {
        $this->eventBus->dispatch($message);
    }
}
