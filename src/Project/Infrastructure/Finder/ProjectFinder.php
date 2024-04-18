<?php

namespace Project\Infrastructure\Finder;

use Project\Domain\Finder\ProjectFinderInterface;
use Project\Domain\View\ReadProjectView;
use Shared\Search\SearchClientInterface;
use Symfony\Component\Uid\Ulid;

final readonly class ProjectFinder implements ProjectFinderInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function readProject(Ulid $id): ?ReadProjectView
    {
        $document = $this->searchClient->findDocument('projects', $id);

        if (null === $document) {
            return null;
        }

        $view = new ReadProjectView();
        $view->id = $id->__toString();
        $view->name = $document->get('name');

        return $view;
    }
}
