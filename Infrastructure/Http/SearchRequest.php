<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request;
use SampleCode\Context\Search\Application\Controller\Message\SearchRequestInterface;
use SampleCode\Context\Search\Application\Controller\Message\SearchResponseInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\FilterGroupBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\SearchCriteriaBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteriaApplierInterface;
use SampleCode\Context\Search\Model\SearchCriteriaInterface;

class SearchRequest implements SearchRequestInterface {

    protected SearchCriteriaApplierInterface $searchCriteriaApplier;

    public function __construct(SearchCriteriaApplierInterface $searchCriteriaApplier)
    {
        $this->searchCriteriaApplier = $searchCriteriaApplier;
    }

    public function search(
        Request $request,
        SearchCriteriaBuilderInterface $searchCriteriaBuilder,
        FilterGroupBuilderInterface $filterGroupBuilder,
    ): SearchResponseInterface
    {
        $searchCriteria = $this->buildSearchCriteriaFromRequest($request, $searchCriteriaBuilder, $filterGroupBuilder);
        return $this->searchCriteriaApplier->apply($searchCriteria);
    }

    private function buildSearchCriteriaFromRequest(
        Request $request,
        SearchCriteriaBuilderInterface $searchCriteriaBuilder,
        FilterGroupBuilderInterface $filterGroupBuilder
    ): SearchCriteriaInterface
    {
        $searchCriteria = $request->query->get('searchCriteria', []);

        if (is_array($searchCriteria) && array_key_exists('groups', $searchCriteria)) {
            foreach ($searchCriteria['groups'] as $filterGroups) {
                $filterGroupBuilder = $this->withFilters($filterGroups['filters'], $filterGroupBuilder);
                $searchCriteriaBuilder = $searchCriteriaBuilder->withFilterGroups($filterGroupBuilder->build());
            }
        }

        return $searchCriteriaBuilder
            ->withPageSize((int)($searchCriteria['pageSize'] ?? 10))
            ->withCurrentPage((int)($searchCriteria['currentPage'] ?? 1))
            ->build();
    }

    private function withFilters(
        array $filters,
        FilterGroupBuilderInterface $filterGroupBuilder
    ): FilterGroupBuilderInterface
    {
        foreach ($filters as $filter) {
            $field = $filter['field'] ?? '';
            $value = $filter['value'] ?? null;
            $condition = $filter['condition'] ?? 'must';
            $filterGroupBuilder = $filterGroupBuilder->withFilter($field, $value, $condition);
        }
        return $filterGroupBuilder;
    }

}
