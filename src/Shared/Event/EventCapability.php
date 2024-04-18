<?php

namespace Shared\Event;

trait EventCapability
{
    private array $events = [];

    public function addEvent($event): void
    {
        $this->events[] = $event;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
