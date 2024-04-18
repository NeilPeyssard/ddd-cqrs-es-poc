<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->connection->delete('event', ['1' => '1']);
    }
}
