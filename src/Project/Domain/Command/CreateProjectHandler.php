<?php

namespace Project\Domain\Command;

use Project\Domain\Project;
use Shared\Bus\EventBusInterface;

final readonly class CreateProjectHandler
{
    public function __construct(
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateProject $command): void
    {
        $project = Project::create($command->getId(), $command->getName());

        foreach ($project->getEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
