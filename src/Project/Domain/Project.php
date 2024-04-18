<?php

namespace Project\Domain;

use Project\Domain\Event\CreateProjectEvent;
use Shared\Event\EventCapability;
use Symfony\Component\Uid\Ulid;

final class Project
{
    use EventCapability;

    private ?Ulid $id = null;
    private ?string $name = null;

    private function __construct()
    {
    }

    public static function create(Ulid $id, string $name): Project
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Project name should not be empty');
        }

        $project = new self();
        $project->id = $id;
        $project->name = $name;

        $project->addEvent(new CreateProjectEvent($project->id, $project->name));

        return $project;
    }

    public static function fromEvents(array $events): Project
    {
        $project = new self();

        foreach ($events as $event) {
            if ($event instanceof CreateProjectEvent) {
                $project->id = $event->getId();
                $project->name = $event->getName();
            }
        }

        return $project;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
