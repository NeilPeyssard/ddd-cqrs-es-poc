<?php

namespace Auth\Domain\Command;

use Symfony\Component\Uid\Ulid;
use Auth\Domain\Finder\UserFinderInterface;
use Auth\Domain\Repository\UserRepositoryInterface;
use Auth\Domain\User;

final readonly class FindUserByEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserFinderInterface $userFinder,
    ) {
    }

    public function __invoke(FindUserByEmail $command): ?User
    {
        $view = $this->userFinder->findUserByEmail($command->getEmail());

        if (null === $view) {
            return null;
        }

        return $this->userRepository->findById(new Ulid($view->id));
    }
}
