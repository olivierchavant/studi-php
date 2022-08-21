<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ProfilRecruteur;
use App\Form\ProfilRecruteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfilRecruteurRegistrationController extends AbstractController
{
    #[Route('/profil/recruteur/registration/{id}', name: 'app_profil_recruteur_registration')]

    public function index(Request $request, EntityManagerInterface $em, int $id = null): Response
    {
        
        if($id) {
            $mode = 'update';
            // On récupère l'article qui correspond à l'id passé dans l'url
            $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $id])[0];
        }
        $form = $this->createForm(ProfilRecruteurType::class, $profilCurrentRecruteur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $em->persist($profilCurrentRecruteur);
            $em->flush();
            $this->addFlash('success', 'Article Created! Knowledge is power!');

        return $this->redirectToRoute('app_default');
        }

        $parameters = array(
            'registrationForm'      => $form->createView(),
            'mode'      => $mode
        );

        return $this->render('profil_recruteur_registration/index.html.twig', $parameters);
    }
}


