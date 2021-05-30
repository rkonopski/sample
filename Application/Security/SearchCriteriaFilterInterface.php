<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Application\Security;

use SampleCode\Context\Search\Model\SearchCriteria\Builder\FilterGroupBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\SearchCriteriaBuilderInterface;
use SampleCode\Context\Security\Model\ActorInterface;

interface SearchCriteriaFilterInterface {

    public function filter(
        ActorInterface $actor,
        SearchCriteriaBuilderInterface $searchCriteriaBuilder,
        FilterGroupBuilderInterface $filterGroupBuilder): SearchCriteriaBuilderInterface;

}
