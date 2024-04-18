<?php

namespace Shared\Search;

use Elastica\Document;
use Symfony\Component\Uid\Ulid;

interface SearchClientInterface
{
    public function createIndex(string $name, array $mapping): void;

    public function createDocument(string $index, Ulid $id, array $data): void;

    public function findDocument(string $index, Ulid $id): ?Document;

    public function searchDocuments(string $index, array $clauses): array;
}
