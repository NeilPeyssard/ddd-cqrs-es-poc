<?php

namespace Auth\Application\EventSubscriber;

use Shared\Event\ApplicationSetupEvent;
use Shared\Search\SearchClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Auth\Domain\Repository\UserRepositoryInterface;

final readonly class UserSetupSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SearchClientInterface $searchClient,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function onSetup(): void
    {
        $this->searchClient->createIndex('users', [
            'email' => ['type' => 'keyword'],
        ]);

        foreach ($this->userRepository->findAll() as $user) {
            $this->searchClient->createDocument('users', $user->getId(), [
                'email' => $user->getEmail(),
            ]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationSetupEvent::class => 'onSetup',
        ];
    }
}
