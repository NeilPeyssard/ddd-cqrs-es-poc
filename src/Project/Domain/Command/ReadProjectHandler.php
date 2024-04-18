<?php

namespace Project\Domain\Command;

use Project\Domain\Finder\ProjectFinderInterface;
use Project\Domain\View\ReadProjectView;

final readonly class ReadProjectHandler
{
    public function __construct(
        private ProjectFinderInterface $projectFinder,
    ) {
    }

    public function __invoke(ReadProject $command): ?ReadProjectView
    {
        return $this->projectFinder->readProject($command->getId());
    }
}
