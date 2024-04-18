<?php

namespace Auth\Infrastructure\Finder;

use Auth\Domain\Finder\OAuthClientFinderInterface;
use Auth\Domain\View\OAuthClientView;
use Shared\Search\SearchClientInterface;

final readonly class OAuthClientFinder implements OAuthClientFinderInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function findByPublicKey(string $publicKey): ?OAuthClientView
    {
        $documents = $this->searchClient->searchDocuments('oauth_clients', ['public_key' => $publicKey]);

        if (!count($documents)) {
            return null;
        }

        $document = $documents[0];
        $view = new OAuthClientView();
        $view->id = $document->getId();
        $view->name = $document->get('name');
        $view->secretKey = $document->get('secret_key');
        $view->allowedGrantTypes = $document->get('allowed_grant_types');
        $view->redirectUrls = $document->get('redirect_urls');

        return $view;
    }
}
