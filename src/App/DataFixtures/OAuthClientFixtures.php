<?php

namespace App\DataFixtures;

use Auth\Domain\OAuthClient;
use Auth\Domain\Repository\OAuthClientRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class OAuthClientFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly OAuthClientRepositoryInterface $oAuthClientRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $client = OAuthClient::create(
            new Ulid('01HV0VB67AYT5SYK6QC0HE2EBD'),
            '018eb331-5352-7b23-8790-65d5669e989b',
            '018eb331-5352-7b23-8790-65d566b3e8c6',
            'Example client',
            [OAuthClient::GRANT_TYPE_AUTH_CODE],
            ['https://foobar.com/authorize'],
        );

        $this->oAuthClientRepository->save($client);
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
