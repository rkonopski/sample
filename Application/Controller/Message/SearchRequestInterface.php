<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Application\Controller\Message;

use Symfony\Component\HttpFoundation\Request;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\FilterGroupBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\SearchCriteriaBuilderInterface;

interface SearchRequestInterface {

    public function search(
        Request $request,
        SearchCriteriaBuilderInterface $searchCriteriaBuilder,
        FilterGroupBuilderInterface $filterGroupBuilder): SearchResponseInterface;

}
