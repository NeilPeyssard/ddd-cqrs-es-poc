<?php

namespace Auth\Domain\Command;

final readonly class FindUserByEmail
{
    public function __construct(
        private string $email,
    ) {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
