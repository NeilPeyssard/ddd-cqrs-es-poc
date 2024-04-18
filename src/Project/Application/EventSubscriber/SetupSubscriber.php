<?php

namespace Project\Application\EventSubscriber;

use Project\Domain\Repository\ProjectRepositoryInterface;
use Shared\Event\ApplicationSetupEvent;
use Shared\Search\SearchClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class SetupSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
        private ProjectRepositoryInterface $projectRepository,
    ) {
    }

    public function onSetup(): void
    {
        $this->searchClient->createIndex('projects', [
            'name' => ['type' => 'text'],
        ]);

        foreach ($this->projectRepository->findAll() as $project) {
            $this->searchClient->createDocument('projects', $project->getId(), [
                'name' => $project->getName(),
            ]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationSetupEvent::class => 'onSetup',
        ];
    }
}
