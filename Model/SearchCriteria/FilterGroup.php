<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria;

class FilterGroup implements FilterGroupInterface {

    /**
     * @var FilterInterface[]
     */
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

}
