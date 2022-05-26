<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Admin;
use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private TranslatorInterface $translator) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
       
        return Dashboard::new()
            ->setTitle('Nicoka Sync')
           ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Project', 'fas fa-list', Project::class);
        yield MenuItem::linkToCrud('Activity', 'fas fa-list', Activity::class);
        yield MenuItem::linkToCrud('Admin', 'fas fa-list', Admin::class);
    }
}


