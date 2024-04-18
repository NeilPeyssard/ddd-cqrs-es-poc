<?php

namespace Auth\Infrastructure\Finder;

use Auth\Domain\Finder\OAuthAccessTokenFinderInterface;
use Auth\Domain\View\OAuthAccessTokenView;
use Shared\Search\SearchClientInterface;
use Symfony\Component\Uid\Ulid;

final readonly class OAuthAccessTokenFinder implements OAuthAccessTokenFinderInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function findById(Ulid $id): ?OAuthAccessTokenView
    {
        $document = $this->searchClient->findDocument('oauth_access_tokens', $id);

        if (null === $document) {
            return null;
        }

        $view = new OAuthAccessTokenView();
        $view->accessToken = $document->get('token');
        $view->expiresAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $document->get('expires_at'));
        $view->userId = $document->get('user_id');

        return $view;
    }

    public function findByToken(string $token): ?OAuthAccessTokenView
    {
        $documents = $this->searchClient->searchDocuments('oauth_access_tokens', ['token' => $token]);

        if (!count($documents)) {
            return null;
        }

        $document = $documents[0];
        $view = new OAuthAccessTokenView();
        $view->accessToken = $document->get('token');
        $view->expiresAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $document->get('expires_at'));
        $view->userId = $document->get('user_id');

        return $view;
    }
}
