<?php

namespace Auth\Application\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Auth\Domain\Command\CreateUser;

final readonly class UserController
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    #[Route(
        path: '/api/user',
        name: 'user_create',
        methods: ['POST'],
    )]
    public function create(
        #[ValueResolver('user.create')]
        CreateUser $command,
    ): JsonResponse {
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->getId()], Response::HTTP_CREATED);
    }
}
