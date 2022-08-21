<?php

namespace App\Controller;

use App\Entity\ProfilCandidat;
use App\Form\ProfilCandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilCandidatRegistrationController extends AbstractController
{
    #[Route('/profil/candidat/registration/{id}', name: 'app_profil_candidat_registration')]
    public function index(Request $request, EntityManagerInterface $em, int $id = null): Response
    {
        
        if($id) {
            $mode = 'update';
            // On récupère l'article qui correspond à l'id passé dans l'url
            $profilCurrentCandidat = $em->getRepository(ProfilCandidat::class)->findBy(['id' => $id])[0];
        }
        $form = $this->createForm(ProfilCandidatType::class, $profilCurrentCandidat);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $em->persist($profilCurrentCandidat);
            $em->flush();
            $this->addFlash('success', 'Article Created! Knowledge is power!');

            return $this->redirectToRoute('app_default');
        }

        $parameters = array(
            'registrationForm'      => $form->createView(),
            'mode'      => $mode
        );

        return $this->render('profil_candidat_registration/index.html.twig', $parameters);
    }
}

// #[Route('/profil/candidat/{id}', name: 'app_profil_candidat')]

// public function edit(EntityManagerInterface $em, Request $request, int $id=null): Response
// {
//     // Entity Manager de Symfony
//     // $em = $this->getDoctrine()->getManager();
//     // Si un identifiant est présent dans l'url alors il s'agit d'une modification
//     // Dans le cas contraire il s'agit d'une création d'article
//     if($id) {
//         $mode = 'update';
//         // On récupère l'article qui correspond à l'id passé dans l'url
//         $user = $em->getRepository(User::class)->findBy(['id' => $id])[0];
//     }
//     $form = $this->createForm(UserType::class, $user);
//     $form->handleRequest($request);

//     if($form->isSubmitted() && $form->isValid()) {
       
//         $em->persist($user);
//         $em->flush();
//         return $this->redirectToRoute('app_users');
//     }

//     $parameters = array(
//         'registrationForm'      => $form->createView(),
//         'user'   => $user,
//         'mode'      => $mode
//     );

//     return $this->render('profil_candidat_registration/index.html.twig', $parameters
//     );
// }
