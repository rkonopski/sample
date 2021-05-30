<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Infrastructure\Security;

use SampleCode\Context\Indexer\Model\IndexInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\SearchCriteriaBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;
use SampleCode\Context\Search\Model\SearchCriteriaInterface;
use SampleCode\Context\Security\Model\ActorInterface;
use SampleCode\Context\Security\Model\FieldAccess;
use SampleCode\Context\Security\Model\FieldAccess\GroupFieldAccess;

class SearchRequestBuilderDecorator implements SearchCriteriaBuilderInterface {

    protected SearchCriteriaBuilderInterface $searchCriteriaBuilder;

    protected ActorInterface $actor;

    protected FieldAccess\GroupFieldAccess $groupFieldAccess;

    protected FieldAccess\Type\ReadFields $readFieldsPermissionChecker;

    public function __construct(
        SearchCriteriaBuilderInterface $searchCriteriaBuilder,
        FieldAccess\Type\ReadFields $readFieldsPermissionChecker)
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->readFieldsPermissionChecker = $readFieldsPermissionChecker;
    }

    public function setGroupFieldAccess(GroupFieldAccess $groupFieldAccess): static
    {
        $this->groupFieldAccess = $groupFieldAccess;
        return $this;
    }

    public function setActor(ActorInterface $actor): static
    {
        $this->actor = $actor;
        return $this;
    }

    public function withFields(string ...$fields): static
    {
        $this->readFieldsPermissionChecker->check($this->groupFieldAccess, $this->actor, $fields);
        $this->searchCriteriaBuilder = $this->searchCriteriaBuilder->withFields(...$fields);
        return $this;
    }

    public function withSortingBy($field, string $sorting = self::SORT_ASC): static
    {
        $this->readFieldsPermissionChecker->check($this->groupFieldAccess, $this->actor, [ $field ]);
        $this->searchCriteriaBuilder = $this->searchCriteriaBuilder->withSortingBy([ $field ], $sorting);
        return $this;
    }

    public function withFilterGroups(FilterGroupInterface ...$filterGroups): static
    {
        $fields = [];
        foreach ($filterGroups as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
            }
        }
        $this->readFieldsPermissionChecker->check($this->groupFieldAccess, $this->actor, array_unique($fields));
        $this->searchCriteriaBuilder = $this->searchCriteriaBuilder->withFilterGroups(...$filterGroups);
        return $this;
    }

    public function withIndex(string $index): static
    {
        $this->searchCriteriaBuilder->withIndex($index);
        return $this;
    }

    public function withPageSize(int $pageSize): static
    {
        $this->searchCriteriaBuilder = $this->searchCriteriaBuilder->withPageSize($pageSize);
        return $this;
    }

    public function withCurrentPage(int $currentPage = 1): static
    {
        $this->searchCriteriaBuilder = $this->searchCriteriaBuilder->withCurrentPage($currentPage);
        return $this;
    }

    public function build(): SearchCriteriaInterface
    {
        return $this->searchCriteriaBuilder->build();
    }

}