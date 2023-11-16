<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie/add', name: 'categorie_add')]
    public function add(ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();

        $categorie = new Categorie();
        $date = new \DateTimeImmutable("now");
        $categorie->setCreatedate($date);

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();

            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }

        return $this->renderForm('categorie/_categorie_add.html.twig', [
            'form' => $form,
        ]);
    }
}
