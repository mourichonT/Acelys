<?php

namespace App\EventListener;

use App\Entity\Activity;
use App\Entity\ActivityLog;
use App\Message\ActivityMessage;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

class ActivitySubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;
    private Security $security;
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        MessageBusInterface $bus,
        Security $security,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->bus = $bus;
        $this->security = $security;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist => 'postPersist',
            Events::preUpdate => 'preUpdate',
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Activity) {
            return;
        }

        $this->bus->dispatch(new ActivityMessage($entity->getId()));
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $changedProperties = $args->getEntityChangeSet();
        if (!$entity instanceof Activity) {
            return;
        }

        $activityLog = (new ActivityLog())
            ->setIsManaged($entity->getIsManaged())
            ->setSummary($entity->getSummary())
            ->setJiraKey($entity->getJiraKey())
            ->setProject($entity->getProject())
            ->setAdmin($this->security->getUser())
            ->setActivity($entity)
        ;


        foreach ($changedProperties as $property => $changedProperty) {
            $setter = 'set'.ucfirst($property);
            $activityLog->$setter($changedProperty[0]);
        }

        $this->em->persist($activityLog);

        $this->em->getEventManager()->removeEventSubscriber($this);
        $this->em->flush();
        $this->em->getEventManager()->addEventSubscriber($this);
    }
}
