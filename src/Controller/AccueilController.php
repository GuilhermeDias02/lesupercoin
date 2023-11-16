<?php

namespace App\Controller;

use App\Entity\Annonce;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $annonces = $doctrine->getRepository(Annonce::class)->findAll();

        return $this->render('accueil/_accueil.html.twig', [
            'annonces' => $annonces,
        ]); // quand il y aura des annonces dans la bdd il faudra les envoyer ici
    }
}
