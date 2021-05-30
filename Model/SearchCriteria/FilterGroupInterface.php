<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria;

interface FilterGroupInterface {

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array;

}
