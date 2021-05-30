<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Model;

use SampleCode\Context\Search\Application\Controller\Message\SearchResponseInterface;

interface SearchCriteriaApplierInterface {

    public function apply(SearchCriteriaInterface $searchCriteria): SearchResponseInterface;

}