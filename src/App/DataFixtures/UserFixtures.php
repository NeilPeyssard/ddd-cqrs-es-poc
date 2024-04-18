<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Uid\Ulid;
use Auth\Domain\Repository\UserRepositoryInterface;
use Auth\Domain\User;

final class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherFactoryInterface $hasherFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $hasher = $this->hasherFactory->getPasswordHasher(User::class);
        $user = User::create(
            new Ulid('01HTHWYWQN6ZQ0S9Z0A3XAWYV4'),
            'john@gmail.com',
            $hasher->hash('cactus'),
        );

        $this->userRepository->save($user);
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
