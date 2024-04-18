<?php

namespace Auth\Domain\Projector;

use Auth\Domain\Event\CreateOAuthAccessTokenEvent;

interface OAuthAccessTokenProjectorInterface
{
    public function create(CreateOAuthAccessTokenEvent $event): void;
}
