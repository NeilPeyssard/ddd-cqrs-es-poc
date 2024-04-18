<?php

namespace Shared\Search;

use Elastica\Client as ElasticaClient;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Elastica\Mapping;
use Elastica\Query;
use Symfony\Component\Uid\Ulid;

final readonly class SearchClient implements SearchClientInterface
{
    public function __construct(
        private ElasticaClient $elasticaClient,
    ) {
    }

    public function createIndex(string $name, array $mapping): void
    {
        $index = $this->elasticaClient->getIndex($name);

        if ($index->exists()) {
            $index->delete();
        }

        $index->create([]);

        $elasticaMapping = new Mapping();
        $elasticaMapping->setProperties($mapping);
        $index->setMapping($elasticaMapping);
    }

    public function createDocument(string $index, Ulid $id, array $data): void
    {
        $elasticaIndex = $this->elasticaClient->getIndex($index);
        $elasticaIndex->addDocument(new Document($id, $data));
    }

    public function findDocument(string $index, Ulid $id): ?Document
    {
        $elasticaIndex = $this->elasticaClient->getIndex($index);

        try {
            $document = $elasticaIndex->getDocument($id->__toString());
        } catch (NotFoundException $exception) {
            return null;
        }

        return $document;
    }

    public function searchDocuments(string $index, array $clauses): array
    {
        $elasticaIndex = $this->elasticaClient->getIndex($index);
        $query = new Query();
        $bool = new Query\BoolQuery();

        foreach ($clauses as $key => $value) {
            $bool->addMust(new Query\Term([$key => $value]));
        }
        $query->setQuery($bool);
        $resultSet = $elasticaIndex->search($query);

        return $resultSet->getDocuments();
    }
}
