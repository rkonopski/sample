<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Application\Security;

use SampleCode\Context\Adoption\Application\Controller\AnnouncementController\Security\SearchCriteriaFilter;

interface FilteredControllerInterface {

    /**
     * @return SearchCriteriaFilter[]
     */
    public function getSearchCriteriaFilters(): array;

}
