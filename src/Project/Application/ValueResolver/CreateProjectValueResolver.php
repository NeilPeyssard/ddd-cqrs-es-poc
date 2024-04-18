<?php

namespace Project\Application\ValueResolver;

use Project\Domain\Command\CreateProject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Ulid;

#[AsTargetedValueResolver('project.create')]
class CreateProjectValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, CreateProject::class, true)) {
            return [];
        }

        $payload = $request->getPayload();

        if (!$payload->has('name')) {
            return [];
        }

        $model = new CreateProject(new Ulid(), $payload->get('name', ''));

        return [$model];
    }
}
