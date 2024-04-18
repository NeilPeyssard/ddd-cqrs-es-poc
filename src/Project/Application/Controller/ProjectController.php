<?php

namespace Project\Application\Controller;

use Project\Domain\Command\CreateProject;
use Project\Domain\Command\ReadProject;
use Shared\Bus\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final readonly class ProjectController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        path: '/api/project/{id}',
        name: 'project_read',
        methods: ['GET'],
    )]
    public function read(
        #[ValueResolver('project.read')]
        ReadProject $command
    ): Response {
        $view = $this->queryBus->dispatch($command);

        if (null === $view) {
            throw new NotFoundHttpException('Project not found');
        }

        return new JsonResponse($view);
    }

    #[Route(
        path: '/api/project',
        name: 'project_create',
        methods: ['POST'],
    )]
    public function create(
        #[ValueResolver('project.create')]
        CreateProject $command
    ): Response {
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->getId()], Response::HTTP_CREATED);
    }
}
