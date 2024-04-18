<?php

namespace Auth\Infrastructure\Finder;

use Shared\Search\SearchClientInterface;
use Auth\Domain\Finder\UserFinderInterface;
use Auth\Domain\View\UserView;

final readonly class UserFinder implements UserFinderInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
    ) {
    }

    public function findUserByEmail(string $email): ?UserView
    {
        $documents = $this->searchClient->searchDocuments('users', ['email' => $email]);

        if (!count($documents)) {
            return null;
        }

        $document = $documents[0];
        $view = new UserView();
        $view->id = $document->getId();
        $view->email = $document->get('email');

        return $view;
    }
}
