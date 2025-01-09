<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Projet API');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        //liste des nationalités
        yield MenuItem::linkToCrud('Liste des Nationlaités', 'fa fa-globe', Nationalite::class);
        //liste des genres
        yield MenuItem::linkToCrud('Liste des Genres', 'fa fa-book', Genre::class);
        //liste des Editeurs
        yield MenuItem::linkToCrud('Liste des Editeurs', 'fa fa-edit', Editeur::class);
        //liste des Auteurs
        yield MenuItem::linkToCrud('Liste des Auteurs', 'fa fa-user', Auteur::class);
        //liste des Livres
        yield MenuItem::linkToCrud('Liste des Livres', 'fa fa-book-open', Livre::class);
        //liste des Adherents
        yield MenuItem::linkToCrud('Liste des Adherents', 'fa fa-address-card', Adherent::class);
        //liste des Pret
        yield MenuItem::linkToCrud('Liste des Prets', 'fa fa-address-card', Pret::class);
       
    }
}
