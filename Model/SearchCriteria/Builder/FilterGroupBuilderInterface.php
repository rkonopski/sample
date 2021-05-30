<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria\Builder;

use SampleCode\Context\Search\Model\SearchCriteria\FilterGroupInterface;

interface FilterGroupBuilderInterface {

    public function withFilter(string $field, string|int|float|null $value, string $condition = 'eq'): static;

    public function build(): FilterGroupInterface;

}
