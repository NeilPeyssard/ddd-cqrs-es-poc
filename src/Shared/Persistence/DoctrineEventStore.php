<?php

namespace Shared\Persistence;

use Doctrine\DBAL\Connection;
use Shared\Event\DomainEventInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler(bus: 'event.bus', handles: DomainEventInterface::class, method: 'add')]
final readonly class DoctrineEventStore implements EventStoreInterface
{
    public function __construct(
        private Connection $connection,
        private SerializerInterface $serializer,
    ) {
    }

    public function findByAggregateType(string $type): array
    {
        $events = [];
        $query = $this->connection->prepare('SELECT * FROM event WHERE aggregate_type = :aggregateType ORDER BY created_at');
        $stmt = $query->executeQuery(['aggregateType' => $type]);

        foreach ($stmt->fetchAllAssociative() as $row) {
            $events[] = $this->serializer->deserialize($row['payload'], $row['type'], 'json');
        }

        return $events;
    }

    public function findById(Ulid $id): array
    {
        $events = [];
        $query = $this->connection->prepare('SELECT * FROM event WHERE uuid = :uuid ORDER BY created_at');
        $stmt = $query->executeQuery(['uuid' => $id]);

        foreach ($stmt->fetchAllAssociative() as $row) {
            $events[] = $this->serializer->deserialize($row['payload'], $row['type'], 'json');
        }

        return $events;
    }

    public function add(DomainEventInterface $event): void
    {
        $this->connection->insert('event', [
            'uuid' => $event->getId(),
            'aggregate_type' => $event->getAggregateType(),
            'type' => get_class($event),
            'payload' => $this->serializer->serialize($event, 'json'),
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);
    }
}
