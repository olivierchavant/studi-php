<?php

namespace App\Controller;

use App\Entity\ProfilRecruteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ProfilRecruteurController extends AbstractController
{
    #[Route('/profil/recruteur', name: 'app_profil_recruteur')]
    public function index(): Response
    {
        return $this->render('profil_recruteur/index.html.twig', [
            'controller_name' => 'ProfilRecruteurController',
        ]);
    }


#[Route('/profil/recruteur/{id} ', name: 'app_profil_recruteur')]

    public function candidat(EntityManagerInterface $em , int $id=null ): Response

    {  
        
        if ($id) { 
            $user = $em->getRepository(User::class)->findBy(["id" => $id])[0]; 
            $recruteurId = $user->getProfilrecruteur();
        } else { 
            $recruteurId = $this->getUser()->getProfilRecruteur()->getId();
            $user = $this->getUser(); 
        }

       $recruteur= $em->getRepository(ProfilRecruteur::class)->findBy(["id" => $recruteurId])[0]; 
        
        // dd($recruteur);
        return $this->render('profil_recruteur/edit.html.twig', [
            'recruteur' => $recruteur,
            'user' => $user
        ]);
    }


}