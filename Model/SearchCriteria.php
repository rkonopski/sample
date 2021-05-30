<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model;

use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;

class SearchCriteria implements SearchCriteriaInterface {

    protected string $index;

    /**
     * @var FilterGroupInterface[]
     */
    private array $filterGroups;

    private int $pageSize;

    private int $currentPage;

    private array $sortingBy;

    private array $fields;

    public function __construct(
        string $index,
        array $filterGroups,
        int $pageSize = 10,
        int $currentPage = 1,
        array $sortingBy = [],
        array $fields = []
    )
    {
        $this->index = $index;
        $this->filterGroups = $filterGroups;
        $this->pageSize = $pageSize;
        $this->currentPage = $currentPage;
        $this->sortingBy = $sortingBy;
        $this->fields = $fields;
    }

    public function getIndexName(): string
    {
        return $this->index;
    }

    public function getFilterGroups(): array
    {
        return $this->filterGroups;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getSortingBy(): array
    {
        return $this->sortingBy;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

}
