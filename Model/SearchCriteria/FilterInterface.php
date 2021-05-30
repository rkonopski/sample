<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria;

interface FilterInterface {

    public function getField(): string;

    public function getValue(): string|int|float|null;

    public function getCondition(): string; // @todo not used anywhere; return like, etc for VALUE

}
