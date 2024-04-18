<?php

namespace App\DataFixtures;

use Auth\Domain\OAuthAccessToken;
use Auth\Domain\OAuthCode;
use Auth\Domain\Repository\OAuthAccessTokenRepositoryInterface;
use Auth\Domain\Repository\OAuthCodeRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class OAuthCodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly OAuthCodeRepositoryInterface $oAuthCodeRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $authCode = OAuthCode::create(
            new Ulid('01HV6355MW3V80NG0PC1VTKCY9'),
            new Ulid('01HV0VB67AYT5SYK6QC0HE2EBD'),
            new Ulid('01HTHWYWQN6ZQ0S9Z0A3XAWYV4'),
            (new \DateTimeImmutable())->add(new \DateInterval('PT5M')),
            new Uuid('018ecbca-387c-72a6-bc00-f2151671d083'),
        );

        $this->oAuthCodeRepository->save($authCode);
    }

    public function getDependencies(): array
    {
        return [
            OAuthClientFixtures::class,
            UserFixtures::class,
        ];
    }
}
