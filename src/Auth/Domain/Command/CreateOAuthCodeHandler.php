<?php

namespace Auth\Domain\Command;

use Auth\Domain\OAuthCode;
use Shared\Bus\EventBusInterface;

final readonly class CreateOAuthCodeHandler
{
    public function __construct(
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateOAuthCode $command): void
    {
        $oAuthCode = OAuthCode::create(
            $command->getId(),
            $command->getClientId(),
            $command->getUserId(),
            $command->getExpiresAt(),
            $command->getToken(),
        );

        foreach ($oAuthCode->getEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
