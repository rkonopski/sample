<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Infrastructure\ElasticSearch;

use Elasticsearch\Client;
use SampleCode\Context\Search\Application\Controller\Message\SearchResponseInterface;
use SampleCode\Context\Search\Infrastructure\Http\SearchResponse;
use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;
use SampleCode\Context\Search\Model\SearchCriteriaApplierInterface;
use SampleCode\Context\Search\Model\SearchCriteriaInterface;

class SearchCriteriaApplier implements SearchCriteriaApplierInterface {

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function apply(SearchCriteriaInterface $searchCriteria): SearchResponseInterface
    {
        $params = $this->prepareParams($searchCriteria);

        $result = $this->client->search($params);
        $items = array_map(fn($hits) => $hits['_source'], $result['hits']['hits']);

        return new SearchResponse($items, $searchCriteria->getCurrentPage());
    }

    private function prepareParams(SearchCriteriaInterface $searchCriteria): array
    {
        $query = [];
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $processedFilterGroup = $this->processFilterGroup($filterGroup);
            if (empty($processedFilterGroup)) {
                continue;
            }
            $query[]['bool'] = $processedFilterGroup;
        }

        $from = ($searchCriteria->getCurrentPage() * $searchCriteria->getPageSize()) - $searchCriteria->getPageSize();
        $params = [
            'index' => $searchCriteria->getIndexName(),
            'body' => [
                'from' => $from,
                'size' => $searchCriteria->getPageSize(),
            ],
        ];
        if (!empty($query)) {
            $params['body']['query']['bool']['must'] = $query;
        }
        if (!empty($searchCriteria->getSortingBy())) {
            foreach ($searchCriteria->getSortingBy() as $sorting) {
                $params['body']['sort'][$sorting['field']] = [ 'order' => $sorting['method'] ];
            }
        }
        if (!empty($searchCriteria->getFields())) {
            $params['_source'] = $searchCriteria->getFields();
        }

        return $params;
    }

    private function processFilterGroup(FilterGroupInterface $filterGroup)
    {
        $queryBool = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $queryBool['should'][]['match'] = [
                $filter->getField() => $filter->getValue(),
            ];
        }

        return $queryBool;
    }

}
