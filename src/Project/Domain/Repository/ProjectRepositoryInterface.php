<?php

namespace Project\Domain\Repository;

use Project\Domain\Project;

interface ProjectRepositoryInterface
{
    public function findAll(): array;

    public function save(Project $project): void;
}
