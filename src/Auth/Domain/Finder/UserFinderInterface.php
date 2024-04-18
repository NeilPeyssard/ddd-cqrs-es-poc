<?php

namespace Auth\Domain\Finder;

use Auth\Domain\View\UserView;

interface UserFinderInterface
{
    public function findUserByEmail(string $email): ?UserView;
}
