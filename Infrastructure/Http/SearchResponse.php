<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Infrastructure\Http;

use ArrayIterator;
use SampleCode\Context\Search\Application\Controller\Message\SearchResponseInterface;

class SearchResponse implements SearchResponseInterface {

    private ?ArrayIterator $collection = null;

    public function __construct(
        private array $items,
        private int $currentPage)
    {
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getCollection(): ArrayIterator
    {
        if ($this->collection === null) {
            $this->collection = new ArrayIterator($this->items);
        }
        return $this->collection;
    }

}
