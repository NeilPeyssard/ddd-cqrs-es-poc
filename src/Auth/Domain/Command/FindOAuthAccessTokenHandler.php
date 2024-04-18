<?php

namespace Auth\Domain\Command;


use Auth\Domain\Finder\OAuthAccessTokenFinderInterface;
use Auth\Domain\View\OAuthAccessTokenView;

final readonly class FindOAuthAccessTokenHandler
{
    public function __construct(
        private OAuthAccessTokenFinderInterface $oAuthAccessTokenFinder,
    ) {
    }

    public function __invoke(FindOAuthAccessToken $command): ?OAuthAccessTokenView
    {
        return $this->oAuthAccessTokenFinder->findById($command->getId());
    }
}
