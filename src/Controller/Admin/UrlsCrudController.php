<?php
namespace App\Controller\Admin;

use App\Controller\Admin\ActivityCrudController;
use App\Controller\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UrlsCrudController extends AbstractController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator )
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function generatedUrl(string $controller)
    {

         // if your application only contains one Dashboard, it's enough
        // to define the controller related to this URL
        $url = $this->adminUrlGenerator
            ->setController(ActivityCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

         // some actions may require to pass additional parameters
        $urlEdit = $this->adminUrlGenerator
            
            ->setDashboard(DashboardController::class)
            ->setController(ActivityCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($this->action->getId())
            ->set('menuIndex', 2)
            ->addSignature()
            
            ->generateUrl();


        $urlProject = $this->adminUrlGenerator
        
            ->setDashboard(DashboardController::class)
            ->setController(ActivityCrudController::class)
            ->setAction(Action::INDEX)
            ->set('menuIndex', 1)
            ->addSignature()

            ->generateUrl();

    }
}
    