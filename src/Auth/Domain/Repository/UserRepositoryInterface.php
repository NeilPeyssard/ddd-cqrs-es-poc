<?php

namespace Auth\Domain\Repository;

use Symfony\Component\Uid\Ulid;
use Auth\Domain\User;

interface UserRepositoryInterface
{
    public function findAll(): array;

    public function findById(Ulid $id): ?User;

    public function save(User $user): void;
}
