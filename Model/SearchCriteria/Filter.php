<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model\SearchCriteria;

class Filter implements FilterInterface {

    protected string $field;

    protected float|null|int|string $value;

    protected string $condition;

    public function __construct(string $field, string|int|float|null $value, string $condition = 'eq')
    {
        $this->field = $field;
        $this->value = $value;
        $this->condition = $condition;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue(): string|int|float|null
    {
        return $this->value;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

}
