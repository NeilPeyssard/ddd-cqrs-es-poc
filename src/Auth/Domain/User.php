<?php

namespace Auth\Domain;

use Shared\Event\EventCapability;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;
use Auth\Domain\Event\CreateUserEvent;

final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use EventCapability;

    private ?Ulid $id = null;
    private ?string $email = null;
    private ?string $password = null;

    private function __construct()
    {
    }

    public static function create(Ulid $id, string $email, string $password): User
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Auth\'s email should not be empty');
        }

        if (empty($password)) {
            throw new \InvalidArgumentException('Auth\'s password should not be empty');
        }

        $user = new self();
        $user->id = $id;
        $user->email = $email;
        $user->password = $password;

        $user->addEvent(new CreateUserEvent($user->id, $user->email, $user->password));

        return $user;
    }

    public static function fromEvents(array $events): User
    {
        $user = new self();

        foreach ($events as $event) {
            if ($event instanceof CreateUserEvent) {
                $user->id = $event->getId();
                $user->email = $event->getEmail();
                $user->password = $event->getPassword();
            }
        }

        return $user;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->getEmail();
    }
}
