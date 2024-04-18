<?php

namespace Project\Domain\Projector;

use Project\Domain\Event\CreateProjectEvent;

interface ProjectProjectorInterface
{
    public function create(CreateProjectEvent $event): void;
}
