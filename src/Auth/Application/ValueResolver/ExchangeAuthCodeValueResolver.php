<?php

namespace Auth\Application\ValueResolver;

use Auth\Domain\Command\ExchangeAuthCode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Ulid;

#[AsTargetedValueResolver('oauth.exchange_auth_code')]
class ExchangeAuthCodeValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, ExchangeAuthCode::class, true)) {
            return [];
        }

        $payload = $request->getPayload();

        if (!$payload->has('public_key') || !$payload->has('secret_key') || !$payload->has('auth_code')) {
            return [];
        }

        $model = new ExchangeAuthCode(
            new Ulid(),
            $payload->get('public_key'),
            $payload->get('secret_key'),
            $payload->get('auth_code'),
        );

        return [$model];
    }
}
