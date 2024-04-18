<?php

namespace Project\Domain\Finder;

use Project\Domain\View\ReadProjectView;
use Symfony\Component\Uid\Ulid;

interface ProjectFinderInterface
{
    public function readProject(Ulid $id): ?ReadProjectView;
}
