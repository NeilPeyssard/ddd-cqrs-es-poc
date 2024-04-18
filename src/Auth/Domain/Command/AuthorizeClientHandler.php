<?php

namespace Auth\Domain\Command;

use Auth\Domain\Finder\OAuthClientFinderInterface;
use Auth\Domain\View\OAuthClientView;

final readonly class AuthorizeClientHandler
{
    public function __construct(
        private OAuthClientFinderInterface $oAuthClientFinder,
    ) {
    }

    public function __invoke(AuthorizeClient $authorizeClient): ?OAuthClientView
    {
        return $this->oAuthClientFinder->findByPublicKey($authorizeClient->getPublicKey());
    }
}
