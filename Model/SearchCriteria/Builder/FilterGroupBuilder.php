<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria\Builder;

use SampleCode\Context\Search\Model\SearchCriteria\Filter;
use SampleCode\Context\Search\Model\SearchCriteria\FilterGroup;
use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;
use SampleCode\Context\Search\Model\SearchCriteria\FilterInterface;

class FilterGroupBuilder implements FilterGroupBuilderInterface {

    /**
     * @var FilterInterface[]
     */
    protected array $filters = [];

    public function withFilter(string $field, float|int|string|null $value, string $condition = 'eq'): static
    {
        $cloned = clone $this;
        $cloned->filters[] = new Filter($field, $value, $condition);
        return $cloned;
    }

    public function build(): FilterGroupInterface
    {
        return new FilterGroup($this->filters);
    }

}
