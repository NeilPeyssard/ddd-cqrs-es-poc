<?php

namespace Auth\Infrastructure\Finder;

use Auth\Domain\Finder\OAuthClientFinderInterface;
use Auth\Domain\Finder\OAuthCodeFinderInterface;
use Auth\Domain\View\OAuthClientView;
use Auth\Domain\View\OAuthCodeView;
use Shared\Search\SearchClientInterface;

final readonly class OAuthCodeFinder implements OAuthCodeFinderInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function findByAuthCode(string $authCode): ?OAuthCodeView
    {
        $documents = $this->searchClient->searchDocuments('oauth_codes', ['token' => $authCode]);

        if (!count($documents)) {
            return null;
        }

        $document = $documents[0];
        $view = new OAuthCodeView();
        $view->id = $document->getId();
        $view->userId = $document->get('user_id');
        $view->expiresAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $document->get('expires_at'));
        $view->token = $document->get('token');

        return $view;
    }
}
