<?php

namespace Auth\Domain\Command;


use Auth\Domain\Finder\OAuthAccessTokenFinderInterface;
use Auth\Domain\View\OAuthAccessTokenView;

final readonly class FindOAuthAccessTokenByTokenHandler
{
    public function __construct(
        private OAuthAccessTokenFinderInterface $oAuthAccessTokenFinder,
    ) {
    }

    public function __invoke(FindOAuthAccessTokenByToken $command): ?OAuthAccessTokenView
    {
        return $this->oAuthAccessTokenFinder->findByToken($command->getToken());
    }
}
