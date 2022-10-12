<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\CandidatsAnnonces;
use App\Entity\ProfilCandidat;
use App\Entity\ProfilRecruteur;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(EntityManagerInterface $em): Response
    {
        $annonces = $em->getRepository(Annonces::class)->findBy(['validated' => true]);
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/annoncesAValider', name: 'app_annonces_AV')]
    public function aValider(EntityManagerInterface $em): Response
    {
        $annonces = $em->getRepository(Annonces::class)->findBy(['validated' => false]);
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }


    #[Route('/annonce/{id}', name: 'app_annonce')]
    public function annonce(EntityManagerInterface $em, int $id=null) 

    {   
        $annonce = $em->getRepository(Annonces::class)->findBy(['id' => $id])[0]; 
        $RecruteurId = $annonce->getProfilRecruteur(); 
        $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
        
        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'recruteur' => $profilCurrentRecruteur
        ]);
    }

    #[Route('/annonceCandidat/{id}', name: 'app_annonce_candidat')]
    public function annonce_candidat(EntityManagerInterface $em, int $id=null) 

    {   
        $annonce = $em->getRepository(Annonces::class)->find(['id' => $id]); 
        // dd($annonce);

        $candidat = $em->getRepository((CandidatsAnnonces::class))->findBy(['annonces' => $id ]); 

         // dd($candidat);
// // 
//         $candidatSelect = $em->getRepository((User::class))->findBy(["id" => $candidat[0]->getId()]); 
//         // $recruteur = $em->getRepository((ProfilRecruteur::class))->find([]) 
//         dd($candidatSelect) ; 
        $RecruteurId = $annonce->getProfilRecruteur(); 
        $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
        
        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'recruteur' => $profilCurrentRecruteur, 
            'candidats' => $candidat , 
            
            
        ]);
    }

    #[Route('/annonce/postuler/{id}', name: 'app_postuler_annonce')]
    public function postuler(EntityManagerInterface $em, int $id=null) 

    {   $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
        
        $association = new CandidatsAnnonces();
        $candidatId = $this->getUser()->getProflCandidatid()->getId(); 
        
        $profilCurrentCandidat = $em->getRepository(ProfilCandidat::class)->findBy(['id' => $candidatId])[0];
        $annonce = $em->getRepository(Annonces::class)->findBy(['id' => $id])[0]; 

        $resultat = $em->getRepository(CandidatsAnnonces::class)->findBy( ['profilCandidat' =>   $candidatId ,  'annonces' => $id ] );
        if ($resultat) 
        { 
            return $this->redirectToRoute('app_annonces');
        } else {
            $association->setValidé(false);
            $association->setProfilCandidat($profilCurrentCandidat); 
            $association->setAnnonces($annonce); 
            $em->persist($association); 
            $em->flush(); 
        }
        return $this->redirectToRoute('app_annonces');
        
    } 


    
    #[Route('/annonces/candidat/{id}', name: 'app_annonces_candidat')]
    public function annonceCandidat(EntityManagerInterface $em, int $id=null) 

    {   
        $candidatId = $this->getUser()->getProflCandidatid()->getId();
        // dd($candidatId); 
        $annonces = $em->getRepository(CandidatsAnnonces::class)->findBy(['profilCandidat' => $candidatId]); 
        // dd($annonces); 
        // $RecruteurId = $annonce->getProfilRecruteur(); 
        // $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
        
        return $this->render('annonces/candidat.html.twig', [
            'annonces' => $annonces,
            
        ]);
    }

    // #[Route('/annonces/candidatValidated/{id}', name: 'app_annonces_candidat_validated')]
    // public function annonceCandidatValidated(EntityManagerInterface $em, int $id=null) 

    // {   
        
    //     $annonces = $em->getRepository(CandidatsAnnonces::class)->findBy(['validé' => false]); 
    //     dd($annonces);

    //     if ($id) { 
    //         $annonces = $em->getRepository(CandidatsAnnonces::class)->findBy(['validé' => false, 'id' => $id]);
    //         $annonces->setValidé(true); 
    //         $em->persist($annonces); 
    //         $em->flush(); 
    //     }
        
    //     return $this->render('annonces/candidat.html.twig', [
    //         'annonces' => $annonces,
            
    //     ]);
    // }
    
    #[Route('/annonce/remove/{id}', name: 'app_annonce_remove')]
    public function remove(EntityManagerInterface $em, int $id): Response
    {
        /// Entity Manager de Symfony
        
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $annonce = $em->getRepository(Annonces::class)->findBy(['id' => $id])[0];

        // L'article est supprimé
        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('app_default');
    }
    
    #[Route('/annonces/recruteur/{id}', name: 'app_annonces_recruteur')]
    public function annonceRecruyteur(EntityManagerInterface $em, int $id=null) 

    {   
        $recruteurId = $this->getUser()->getProfilRecruteur()->getId();

        //dd($recruteurId); 

        $annonces = $em->getRepository(Annonces::class)->findBy(['profilRecruteur' => $recruteurId]); 

        // dd($annonces); 
        // $RecruteurId = $annonce->getProfilRecruteur(); 
        // $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
        
        return $this->render('annonces/recruteur.html.twig', [
            'annonces' => $annonces,
            
        ]);
    }




}

