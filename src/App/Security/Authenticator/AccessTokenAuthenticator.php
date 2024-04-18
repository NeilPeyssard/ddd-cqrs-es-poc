<?php

namespace App\Security\Authenticator;

use Auth\Domain\Command\FindOAuthAccessTokenByToken;
use Auth\Domain\Repository\UserRepositoryInterface;
use Shared\Bus\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Uid\Ulid;

final class AccessTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $accessToken = substr($request->headers->get('Authorization'), 6);
        $accessToken = trim($accessToken);

        if (empty($accessToken)) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $accessTokenView = $this->queryBus->dispatch(new FindOAuthAccessTokenByToken($accessToken));

        if (!$accessTokenView || $accessTokenView->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Invalid access token');
        }

        $user = $this->userRepository->findById(new Ulid($accessTokenView->userId));

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid access token');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
