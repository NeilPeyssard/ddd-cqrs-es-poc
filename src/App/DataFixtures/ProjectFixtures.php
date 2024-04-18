<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Project\Domain\Project;
use Project\Domain\Repository\ProjectRepositoryInterface;
use Symfony\Component\Uid\Ulid;

final class ProjectFixtures extends Fixture
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $project = Project::create(new Ulid('01HTHWYWQN6ZQ0S9Z0A3XAWYS2'), 'Test project');

        $this->projectRepository->save($project);
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
