<?php

namespace Auth\Infrastructure\Repository;

use Shared\Persistence\EventStoreInterface;
use Symfony\Component\Uid\Ulid;
use Auth\Domain\Repository\UserRepositoryInterface;
use Auth\Domain\User;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function findAll(): array
    {
        $eventsByUser = $users = [];

        foreach ($this->eventStore->findByAggregateType(User::class) as $event) {
            if (!isset($eventsByUser[$event->getId()->__toString()])) {
                $eventsByUser[$event->getId()->__toString()] = [];
            }

            $eventsByUser[$event->getId()->__toString()][] = $event;
        }

        foreach ($eventsByUser as $userEvents) {
            $users[] = User::fromEvents($userEvents);
        }

        return $users;
    }

    public function findById(Ulid $id): ?User
    {
        $events = $this->eventStore->findById($id);

        return User::fromEvents($events);
    }

    public function save(User $user): void
    {
        foreach ($user->getEvents() as $event) {
            $this->eventStore->add($event);
        }
    }
}
