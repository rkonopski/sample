<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria\Builder;

use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;
use SampleCode\Context\Search\Model\SearchCriteriaInterface;

interface SearchCriteriaBuilderInterface {

    public const
        SORT_ASC = 'ASC',
        SORT_DESC = 'DESC';

    public function withIndex(string $index): static;

    public function withFilterGroups(FilterGroupInterface ...$filterGroups): static;

    public function withFields(string ...$fields): static;

    public function withPageSize(int $pageSize): static;

    public function withCurrentPage(int $currentPage = 1): static;

    public function withSortingBy($field, string $sorting = self::SORT_ASC): static;

    public function build(): SearchCriteriaInterface;

}
