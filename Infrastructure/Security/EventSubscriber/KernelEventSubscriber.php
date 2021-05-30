<?php declare(strict_types=1);

namespace SampleCode\Context\Search\Infrastructure\Security\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SampleCode\Context\Auth\Infrastructure\Security\UserActor;
use SampleCode\Context\Search\Application\Controller\Message\SearchRequestInterface;
use SampleCode\Context\Search\Application\Security\FilteredControllerInterface;
use SampleCode\Context\Search\Infrastructure\Security\SearchRequestDecorator;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\FilterGroupBuilderInterface;
use SampleCode\Context\Search\Model\SearchCriteria\Builder\SearchCriteriaBuilderInterface;
use SampleCode\Context\Security\Application\Controller\SecuredControllerInterface;
use SampleCode\Context\Security\Model\ActorIntentionInterface;
use SampleCode\Context\Security\Model\FieldAccess\GroupFieldAccess;
use SampleCode\Context\Security\Model\FieldAccess\Type\ReadFields;

// @todo refactor
class KernelEventSubscriber implements EventSubscriberInterface {

    protected UserActor $userActor;

    protected ReadFields $readFields;

    protected ActorIntentionInterface $actorIntention;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => [
                [ 'onSecuredControllerArguments', 10 ],
                [ 'onFilteredControllerArguments', 0 ],
            ],
        ];
    }

    public function __construct(UserActor $userActor, ActorIntentionInterface $actorIntention, ReadFields $readFields)
    {
        $this->userActor = $userActor;
        $this->actorIntention = $actorIntention;
        $this->readFields = $readFields;
    }

    public function onSecuredControllerArguments(ControllerArgumentsEvent $event): void
    {
        $controller = $this->getControllerFromEvent($event);
        if (!$controller instanceof SecuredControllerInterface) {
            return;
        }

        $groupFieldAccess = $this->getGroupFieldAccess($controller);
        if (is_null($groupFieldAccess)) {
            return;
        }

        $arguments = $event->getArguments();
        foreach ($arguments as $argumentKey => $argument) {
            if (!$argument instanceof SearchRequestInterface) {
                continue;
            }
            $arguments[$argumentKey] = new SearchRequestDecorator(
                $argument,
                $this->userActor,
                $this->readFields,
                $groupFieldAccess);
        }
        $event->setArguments($arguments);
    }

    public function onFilteredControllerArguments(ControllerArgumentsEvent $event): void
    {
        $controller = $this->getControllerFromEvent($event);
        if (!$controller instanceof FilteredControllerInterface) {
            return;
        }

        $arguments = $event->getArguments();
        $filterGroupBuilder = $this->getArgumentOfInstance($arguments, FilterGroupBuilderInterface::class);

        if (is_null($filterGroupBuilder)) {
            return;
        }

        foreach ($arguments as $argumentKey => $argument) {
            if (!$argument instanceof SearchCriteriaBuilderInterface) {
                continue;
            }
            $actor = $this->userActor->get();
            foreach ($controller->getSearchCriteriaFilters() as $filter) {
                $arguments[$argumentKey] = $filter->filter($actor, $argument, $filterGroupBuilder);
            }
        }
        $event->setArguments($arguments);
    }

    private function getArgumentOfInstance($arguments, $class)
    {
        foreach ($arguments as $argument) {
            if (!$argument instanceof $class) {
                continue;
            }
            return $argument;
        }
        return null;
    }

    private function getControllerFromEvent(ControllerArgumentsEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }
        return $controller;
    }

    private function getGroupFieldAccess(SecuredControllerInterface $controller): ?GroupFieldAccess
    {
        foreach ($controller->getGuards($this->userActor->get(), $this->actorIntention) as $guard) {
            if (!$guard instanceof GroupFieldAccess) {
                continue;
            }
            // @todo guard cannot be used twice
            return $guard;
        }

        return null;
    }

}
