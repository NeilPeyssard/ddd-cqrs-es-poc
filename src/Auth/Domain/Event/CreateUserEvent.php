<?php

namespace Auth\Domain\Event;

use Auth\Domain\User;
use Shared\Event\DomainEventInterface;
use Symfony\Component\Uid\Ulid;

final readonly class CreateUserEvent implements DomainEventInterface
{
    public function __construct(
        private Ulid $id,
        private string $email,
        private string $password,
    ) {
    }

    public function getId(): Ulid
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

    public function getAggregateType(): string
    {
        return User::class;
    }
}
