<?php

namespace Project\Application\ValueResolver;

use Project\Domain\Command\CreateProject;
use Project\Domain\Command\ReadProject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Ulid;

#[AsTargetedValueResolver('project.read')]
class ReadProjectValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, ReadProject::class, true)) {
            return [];
        }

        if (!$request->attributes->has('id')) {
            return [];
        }

        $id = new Ulid($request->attributes->get('id', ''));
        $model = new ReadProject($id);

        return [$model];
    }
}
