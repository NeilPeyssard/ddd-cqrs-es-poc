<?php

namespace Project\Infrastructure\Projector;

use Project\Domain\Event\CreateProjectEvent;
use Project\Domain\Projector\ProjectProjectorInterface;
use Shared\Search\SearchClientInterface;

final readonly class ProjectProjector implements ProjectProjectorInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function create(CreateProjectEvent $event): void
    {
        $this->searchClient->createDocument('projects', $event->getId(), ['name' => $event->getName()]);
    }
}
