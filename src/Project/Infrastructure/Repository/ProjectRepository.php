<?php

namespace Project\Infrastructure\Repository;

use Project\Domain\Project;
use Project\Domain\Repository\ProjectRepositoryInterface;
use Shared\Persistence\EventStoreInterface;
use Symfony\Component\Uid\Ulid;

final readonly class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function findAll(): array
    {
        $eventsByProject = $projects = [];

        foreach ($this->eventStore->findByAggregateType(Project::class) as $event) {
            if (!isset($eventsByProject[$event->getId()->__toString()])) {
                $eventsByProject[$event->getId()->__toString()] = [];
            }

            $eventsByProject[$event->getId()->__toString()][] = $event;
        }

        foreach ($eventsByProject as $projectEvents) {
            $projects[] = Project::fromEvents($projectEvents);
        }

        return $projects;
    }

    public function save(Project $project): void
    {
        foreach ($project->getEvents() as $event) {
            $this->eventStore->add($event);
        }
    }
}
