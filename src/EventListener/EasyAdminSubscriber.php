<?php

namespace App\EventListener;


use App\Entity\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setEncodedPassword'],

        ];
    }
    public function setEncodedPassword(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if (!($entity instanceof Admin)) {
            return;
        }
        if ($entity->getPlainPassword()) {
            $entity->setPassword($this->passwordHasher->hashPassword($entity, $entity->getPlainPassword()));
        }
    }
}