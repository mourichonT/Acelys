<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Message\ActivityMessage;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Messenger\MessageBusInterface;

class ActivityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Activity::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('jiraKey'),
            TextField::new('summary'),
            AssociationField::new('project'),
            BooleanField::new('isManaged'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $resync = Action::new('resync', 'Resync')
            ->displayIf(fn($entity) => !$entity->getIsManaged())
            ->linkToCrudAction('resync');

        return $actions
            ->add(Crud::PAGE_INDEX, $resync);
    }

    public function resync(AdminContext $context, MessageBusInterface $bus)
    {
        $activity = $context->getEntity()->getInstance();
        $bus->dispatch(new ActivityMessage($activity->getId()));

        $url = $context->getReferrer()
        ?? $this->container->get(AdminUrlGenerator::class)->setAction(Action::INDEX)->generateUrl();

        return $this->redirect($url);
    }
}
