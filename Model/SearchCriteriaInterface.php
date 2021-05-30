<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model;

use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;

interface SearchCriteriaInterface {

    public function getIndexName(): string;

    /**
     * @return FilterGroupInterface[]
     */
    public function getFilterGroups(): array;

    public function getPageSize(): int;

    public function getCurrentPage(): int;

    public function getSortingBy(): array;

}