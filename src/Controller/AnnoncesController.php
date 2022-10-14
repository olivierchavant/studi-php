<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\CandidatsAnnonces;
use App\Entity\ProfilCandidat;
use App\Entity\ProfilRecruteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    // liste des annonces validée par le consultant 
    public function index(EntityManagerInterface $em): Response
    {
        $annonces = $em->getRepository(Annonces::class)->findBy(['validated' => true]);
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/annoncesAValider', name: 'app_annonces_AV')]
    // liste des annonces à àvalider par la consultant 
    public function aValider(EntityManagerInterface $em): Response
    {
        $annonces = $em->getRepository(Annonces::class)->findBy(['validated' => false]);
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }


    #[Route('/annonce/{id}', name: 'app_annonce')]
    // recherche annonce par ID 
    public function annonce(EntityManagerInterface $em, int $id=null) 

    {   $mode = null ; 
        $annonce = $em->getRepository(Annonces::class)->findBy(['id' => $id])[0]; 
        $RecruteurId = $annonce->getProfilRecruteur(); 
        $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
        
        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'recruteur' => $profilCurrentRecruteur,
            'mode' => $mode
        ]);
    }

    #[Route('/annonceCandidat/{id}', name: 'app_annonce_candidat')]
    // recherche annoces par candidat 
    public function annonce_candidat(EntityManagerInterface $em, int $id=null ) 

    {   if($id){ 
        $mode = 'update'; 
       }; 
        $annonce = $em->getRepository(Annonces::class)->find(['id' => $id]); 
        $candidat = $em->getRepository((CandidatsAnnonces::class))->findBy(['annonces' => $id ]); 
        $RecruteurId = $annonce->getProfilRecruteur(); 
        $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
       
        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'recruteur' => $profilCurrentRecruteur, 
            'candidats' => $candidat , 
            'mode' => $mode
        ]);
    }


    #[Route('/annonce/postuler/{id}', name: 'app_postuler_annonce')]
    // alimentation de la table associative CandidatsAnnonces pour poser sa candidature à une annonce
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
    // liste des annonces par Candidat 
    public function annonceCandidat(EntityManagerInterface $em, int $id=null) 

    {   
        $candidatId = $this->getUser()->getProflCandidatid()->getId();
        $annonces = $em->getRepository(CandidatsAnnonces::class)->findBy(['profilCandidat' => $candidatId]); 
 
        return $this->render('annonces/candidat.html.twig', [
            'annonces' => $annonces
            
            
        ]);
    }


    #[Route('/annonce/remove/{id}', name: 'app_annonce_remove')]
    // suppression de l'annonce en cascade 
    public function remove(EntityManagerInterface $em, int $id): Response
    {
        
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $annonce = $em->getRepository(Annonces::class)->findBy(['id' => $id])[0];
        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('app_default');
    }
    
    #[Route('/annonces/recruteur/{id}', name: 'app_annonces_recruteur')]
    // liste des annonces par Recruteur 
    public function annonceRecruyteur(EntityManagerInterface $em, int $id=null) 

    {   
        $recruteurId = $this->getUser()->getProfilRecruteur()->getId();

        $annonces = $em->getRepository(Annonces::class)->findBy(['profilRecruteur' => $recruteurId]);
        
        return $this->render('annonces/recruteur.html.twig', [
            'annonces' => $annonces,
            
        ]);
    }




}

