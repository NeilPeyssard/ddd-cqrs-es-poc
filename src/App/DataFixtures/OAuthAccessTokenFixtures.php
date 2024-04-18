<?php

namespace App\DataFixtures;

use Auth\Domain\OAuthAccessToken;
use Auth\Domain\OAuthClient;
use Auth\Domain\Repository\OAuthAccessTokenRepositoryInterface;
use Auth\Domain\Repository\OAuthClientRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class OAuthAccessTokenFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly OAuthAccessTokenRepositoryInterface $oAuthAccessTokenRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $accessToken = OAuthAccessToken::create(
            new Ulid(Ulid::generate()),
            new Ulid('01HV0VB67AYT5SYK6QC0HE2EBD'),
            new Ulid('01HTHWYWQN6ZQ0S9Z0A3XAWYV4'),
            (new \DateTimeImmutable())->add(new \DateInterval('PT30M')),
            new Uuid('018eb331-5352-7b23-8790-65d5669e988a'),
        );

        $this->oAuthAccessTokenRepository->save($accessToken);

        $expiredAccessToken = OAuthAccessToken::create(
            new Ulid(Ulid::generate()),
            new Ulid('01HV0VB67AYT5SYK6QC0HE2EBD'),
            new Ulid('01HTHWYWQN6ZQ0S9Z0A3XAWYV4'),
            (new \DateTimeImmutable())->sub(new \DateInterval('PT30M')),
            new Uuid('018ecbca-387c-72a6-bc00-f2151671d083'),
        );

        $this->oAuthAccessTokenRepository->save($expiredAccessToken);
    }

    public function getDependencies(): array
    {
        return [
            OAuthClientFixtures::class,
        ];
    }
}
