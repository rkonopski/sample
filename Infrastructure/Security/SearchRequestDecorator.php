<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Infrastructure\Security;

use Symfony\Component\HttpFoundation\Request;
use SampleCode\Context\Auth\Infrastructure\Security\UserActor;
use SampleCode\Context\Search\Application\Controller\Message\SearchRequestInterface;
use SampleCode\Context\Search\Application\Controller\Message\SearchResponseInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\FilterGroupBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\SearchCriteriaBuilderInterface;
use SampleCode\Context\Security\Model\FieldAccess\GroupFieldAccess;
use SampleCode\Context\Security\Model\FieldAccess\Type\ReadFields;

class SearchRequestDecorator implements SearchRequestInterface {

    protected ReadFields $readFields;

    protected GroupFieldAccess $fieldLevelPermission;

    protected SearchRequestInterface $searchRequest;

    protected UserActor $userActor;

    public function __construct(
        SearchRequestInterface $searchRequest,
        UserActor $userActor,
        ReadFields $readFields,
        GroupFieldAccess $fieldLevelPermission)
    {
        $this->readFields = $readFields;
        $this->fieldLevelPermission = $fieldLevelPermission;
        $this->searchRequest = $searchRequest;
        $this->userActor = $userActor;
    }

    public function search(
        Request $request,
        SearchCriteriaBuilderInterface $searchCriteriaBuilder,
        FilterGroupBuilderInterface $filterGroupBuilder): SearchResponseInterface
    {
        $actor = $this->userActor->get();
        $searchRequestBuilderDecorator = new SearchRequestBuilderDecorator($searchCriteriaBuilder, $this->readFields);
        $searchRequestBuilderDecorator
            ->setActor($actor)
            ->setGroupFieldAccess($this->fieldLevelPermission)
            ->withFields(...$this->fieldLevelPermission->getAllowedReadFields($actor));

        return $this->searchRequest->search($request, $searchRequestBuilderDecorator, $filterGroupBuilder);
    }

}
