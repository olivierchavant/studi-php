<?php

namespace App\Controller;

use App\Entity\ProfilCandidat;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilCandidatController extends AbstractController
{
    #[Route('/profil/candidats', name: 'app_profil_candidats')]
    // liste des profils candidat 
    public function index(EntityManagerInterface $em ): Response

    {   $user = $em->getRepository(User::class)->findBy(["profil" => "Candidat"]); 
       
        
        return $this->render('profil_candidat/index.html.twig', [
            'candidats' => $user,
        ]);
    }

    #[Route('/profil/candidat/{id} ', name: 'app_profil_candidat')]
    // réccupération d'un profil candidat 
    public function candidat(EntityManagerInterface $em , int $id=null ): Response

    {  
        
        if ($id) { 
            $user = $em->getRepository(User::class)->findBy(["id" => $id])[0]; 
            $candidatId = $user->getProflCandidatid();
        } else { 
            $candidatId = $this->getUser()->getProflCandidatid()->getId();
            $user = $this->getUser(); 
        }

       $candidat = $em->getRepository(ProfilCandidat::class)->findBy(["id" => $candidatId])[0]; 
        

        return $this->render('profil_candidat/edit.html.twig', [
            'candidat' => $candidat,
            'user' => $user
        ]);
    }

    #[Route('/profil/candidatRecrutreur/{id} ', name: 'app_profil_candidat_r')]
    // récupération profil Candidat par recruteur 
    public function candidatp(EntityManagerInterface $em , int $id=null ): Response

    {  

       $candidat = $em->getRepository(ProfilCandidat::class)->findBy(["id" => $id])[0]; 
        
        return $this->render('profil_candidat/edit.html.twig', [
            'candidat' => $candidat,
            
        ]);
    }


        #[Route('/download/{cv} ', name: 'app_download_cv')]
        // 
        public function download($cv) 
    {
   
        return $this->file($this->getParameter('cv_directory').$cv); // Paramètre cv_directory dans service.YAML
    }
}


