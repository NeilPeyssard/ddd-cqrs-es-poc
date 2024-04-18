<?php

namespace Auth\Application\Controller;

use Auth\Application\Dto\AuthorizeOAuthClientDto;
use Auth\Application\Form\AuthorizeType;
use Auth\Domain\Command\AuthorizeClient;
use Auth\Domain\Command\CreateOAuthCode;
use Auth\Domain\Command\ExchangeAuthCode;
use Auth\Domain\Command\FindOAuthAccessToken;
use Shared\Bus\QueryBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;
use Twig\Environment;

final readonly class OAuthController
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private Environment $twig,
        private MessageBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        path: '/authorize',
        name: 'auth_authorize',
        methods: ['GET', 'POST'],
    )]
    public function authorize(
        FormFactoryInterface $formFactory,
        #[MapQueryString]
        AuthorizeOAuthClientDto $authorizeClientDto,
        Request $request,
    ): Response {
        $oAuthClient = $this->queryBus->dispatch(new AuthorizeClient($authorizeClientDto->getPublicKey()));

        if (!$oAuthClient) {
            throw new NotFoundHttpException('OAuth client not found');
        }

        if (
            !in_array($authorizeClientDto->getRedirectUrl(), $oAuthClient->redirectUrls) ||
            !in_array($authorizeClientDto->getGrantType(), $oAuthClient->allowedGrantTypes)
        ) {
            throw new BadRequestException('OAuth client in misconfigured');
        }

        $form = $formFactory->create(AuthorizeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->tokenStorage->getToken()?->getUser();
            $createOAuthCodeCommand = new CreateOAuthCode(
                new Ulid(),
                new Ulid($oAuthClient->id),
                $user->getId(),
                (new \DateTimeImmutable())->add(new \DateInterval('PT5M')),
                Uuid::v7(),
            );
            $this->commandBus->dispatch($createOAuthCodeCommand);

            return new RedirectResponse(sprintf(
                '%s?auth_code=%s',
                $authorizeClientDto->getRedirectUrl(),
                $createOAuthCodeCommand->getToken()->__toString()
            ));
        }

        return new Response($this->twig->render('authorize/index.html.twig', [
            'oAuthClient' => $oAuthClient,
            'form' => $form->createView(),
        ]));
    }

    #[Route(
        path: '/token',
        name: 'auth_token',
        methods: ['POST'],
    )]
    public function exchange(
        #[ValueResolver('oauth.exchange_auth_code')]
        ExchangeAuthCode $exchangeAuthCode,
    ): JsonResponse {
        $this->commandBus->dispatch($exchangeAuthCode);
        $oAuthTokenView = $this->queryBus->dispatch(new FindOAuthAccessToken($exchangeAuthCode->getOAuthAccessTokenId()));

        if (!$oAuthTokenView) {
            throw new \LogicException('Cannot retrieve access token');
        }

        return new JsonResponse([
            'access_token' => $oAuthTokenView->accessToken,
            'expires_at' => $oAuthTokenView->expiresAt->format(\DateTimeInterface::ATOM),
        ]);
    }
}
