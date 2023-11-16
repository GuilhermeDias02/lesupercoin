<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/show/{id}', name: 'app_annonce', requirements: ['annonce' => '\d+'])]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $annonce = $entityManager->getRepository(Annonce::class)->find($id);
        $related = $entityManager->getRepository(Annonce::class)->findBy(['categorie' => $annonce->getCategorie()], null, 6);

        if (!$annonce || !$related) {
            return new Response('il n\'y a aucun');
        }
        return $this->render('annonce/_annonce.html.twig', [
            'annonce' => $annonce,
            'related' => $related,
        ]);
    }

    #[Route('/annonce/add', name: 'annonce_add')]
    public function add(ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();
        
        $annonce = new Annonce();
        $date = new \DateTimeImmutable("now");
        $annonce->setCreatedate($date);

        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $form->getData();

            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce_add_succes');
        }

        return $this->renderForm('annonce/_annonce_add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/annonce/modif/{id}', name:'annonce_modif')]
    public function modif(EntityManagerInterface $entityManager, $id, Request $request){
        {
            $annonce = $entityManager->getRepository(Annonce::class)->find($id);
    
            $form = $this->createForm(AnnonceType::class, $annonce);
    
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $annonce = $form->getData();
                
                $annonce->setUpdatedate(new \DateTimeImmutable('now'));

                $entityManager->persist($annonce);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_annonce', ['id'=> $annonce->getId()]);
            }
    
            return $this->renderForm('annonce/_annonce_add.html.twig', [
                'form' => $form,
            ]);
        }
    }

    #[Route('annonce/add/succes', name:'annonce_add_succes')]
    public function succes(){
        return $this->render('annonce/_annonce_succes.html.twig');
    }

    #[Route("/annonce/delete/{id}", name:"annonce_delete")]
    public function delete(ManagerRegistry $doctrine, $id){
        $annonce = $doctrine->getRepository(Annonce::class)->find($id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_accueil");
    }
}
