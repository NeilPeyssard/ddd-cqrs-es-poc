<?php

namespace Auth\Domain\Projector;

use Auth\Domain\Event\CreateOAuthCodeEvent;

interface OAuthCodeProjectorInterface
{
    public function create(CreateOAuthCodeEvent $event): void;
}
