<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use App\Repository\AdherentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    /**
     * renvoie le nombre de prêts par adherent
     * @Route(
     *      path="apiPlatform/adherents/nbPretsParAdherent",
     *      name="adherents_nbPrets",
     *      methods={"GET"}
     * )
     */
    public function nombrePretsParAdherent(AdherentRepository $repo)
    {
        $nbPretParAdherent = $repo->nbPretsParAdherent();
        return $this->json($nbPretParAdherent);
    }

    /**
     * Renvoie les 5 meilleurs livres
     * @Route(
     *      path="apiPlatform/livres/meilleurslivres",
     *      name="meilleurslivres",
     *      methods={"GET"}
     * )
     */
    public function meilleursLivres(LivreRepository $repo)
    {
        $meilleurLivres = $repo->TrouveMeilleursLivres();
        return $this->json($meilleurLivres);
    }
}