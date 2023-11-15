<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/{annonce}', name: 'app_annonce', requirements: ['annonce' => '\d+'])]
    public function index($annonce): Response
    {
        if (isset($annonce)){
            return $this->render('annonce/_annonce.html.twig', [
                'annonce' => $annonce,
            ]);
        }
        else{
            return $this->redirectToRoute('app_accueil');
        }
    }
}
