<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('nicokaId'),
            TextField::new('jiraKey'),
            IntegerField::new('tjm'),
            AssociationField::new('admins')->onlyOnForms(),
            CollectionField::new('admins')->onlyOnIndex(),
        ];
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }
}
