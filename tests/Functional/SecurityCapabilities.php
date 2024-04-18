<?php

namespace Tests\Functional;

use Auth\Domain\Repository\UserRepositoryInterface;
use Behat\Step\Given;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

trait SecurityCapabilities
{
    private ?KernelBrowser $client;
    private ?UserInterface $user;
    private ?string $accessToken;

    #[Given('I am an anonymous user')]
    public function iAmAnAnonymousUser(): void
    {
        $this->user = null;
    }

    #[Given('I am an authenticated user with id :id')]
    public function iAmAnAuthenticatedUserWithId(string $id): void
    {
        $userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $user = $userRepository->findById(new Ulid($id));
        $this->client->loginUser($user);
    }

    #[Given('I have an access token :token')]
    public function iHaveAnAccessToken(string $token): void
    {
        $this->accessToken = $token;
    }
}
