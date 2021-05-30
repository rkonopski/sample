<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Application\Controller\Message;

use ArrayIterator;

interface SearchResponseInterface {

    public function getCurrentPage(): int;

    public function getCollection(): ArrayIterator;

}