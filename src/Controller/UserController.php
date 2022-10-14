<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    // liste de tous les utilisateurs 
    public function index(EntityManagerInterface $em ): Response
    {

        $user = $em->getRepository(User::class)->findAll(); 

        return $this->render('user/index.html.twig', [
            'users' => $user,
        ]);
    }


  
    #[Route('/user/{id}', name: 'app_user')]
    // Modification d'un compte USER 

    public function edit(EntityManagerInterface $em, Request $request, int $id=null): Response
    {
        // Si un identifiant est présent dans l'url alors il s'agit d'une modification
        // Dans le cas contraire il s'agit d'une création d'article
        if($id) {
            $mode = 'update';
            // On récupère l'article qui correspond à l'id passé dans l'url
            $user = $em->getRepository(User::class)->findBy(['id' => $id])[0];
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_users');
        }

        $parameters = array(
            'registrationForm'      => $form->createView(),
            'user'   => $user,
            'mode'      => $mode
        );

        return $this->render('user/edit.html.twig', $parameters);
    }

    #[Route('/user/remove/{id}', name: 'app_user_remove')]

    // Suppression d'un compte USER en cascade 
    public function remove(EntityManagerInterface $em, int $id): Response
    {
        /// Entity Manager de Symfony
        
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $user = $em->getRepository(User::class)->findBy(['id' => $id])[0];

        // L'article est supprimé
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_users');
    }


    
}
