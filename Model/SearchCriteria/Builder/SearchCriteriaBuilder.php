<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria\Builder;

use SampleCode\Context\Search\Model\SearchCriteria;
use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;
use SampleCode\Context\Search\Model\SearchCriteriaInterface;

class SearchCriteriaBuilder implements SearchCriteriaBuilderInterface {

    private string $index;

    private array $filterGroups = [];

    private int $pageSize = 10;

    private int $currentPage = 1;

    private array $sortingBy = [];

    private array $fields = [];

    public function withIndex(string $index): static
    {
        $cloned = clone $this;
        $cloned->index = $index;
        return $cloned;
    }

    public function withFilterGroups(FilterGroupInterface ...$filterGroups): static
    {
        $cloned = clone $this;
        $cloned->filterGroups = array_merge($this->filterGroups, $filterGroups);
        return $cloned;
    }

    public function withFields(string ...$fields): static
    {
        $cloned = clone $this;
        $cloned->fields = array_merge($this->fields, $fields);
        return $cloned;
    }

    public function withSortingBy($field, string $sorting = self::SORT_ASC): static
    {
        $cloned = clone $this;
        $cloned->sortingBy[] = [
            'field' => $field,
            'method' => $sorting,
        ];
        return $cloned;
    }

    public function withPageSize(int $pageSize): static
    {
        $cloned = clone $this;
        $cloned->pageSize = $pageSize;
        return $cloned;
    }

    public function withCurrentPage(int $currentPage = 1): static
    {
        $cloned = clone $this;
        $cloned->currentPage = $currentPage;
        return $cloned;
    }

    public function build(): SearchCriteriaInterface
    {
        return new SearchCriteria(
            $this->index,
            $this->filterGroups,
            $this->pageSize,
            $this->currentPage,
            $this->sortingBy,
            $this->fields
        );
    }

}
