<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\CandidatsAnnonces;
use App\Entity\ProfilCandidat;
use App\Entity\ProfilRecruteur;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(EntityManagerInterface $em): Response
    {
        $annonces = $em->getRepository(Annonces::class)->findAll();
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

  


    #[Route('/annonce/postuler/{id}', name: 'app_postuler_annonce')]
    public function postuler(EntityManagerInterface $em, int $id=null) 

    {   
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
        
    } #[Route('/annonces/candidat/{id}', name: 'app_annonces_candidat')]
    public function annonceCandidat(EntityManagerInterface $em, int $id=null) 

    {   $candidatId = $this->getUser()->getProflCandidatid()->getId();

        $annonce = $em->getRepository(CandidatsAnnonces::class)->findBy(['id' => $candidatId]); 

        // $RecruteurId = $annonce->getProfilRecruteur(); 
        // $profilCurrentRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy(['id' => $RecruteurId])[0];
        
        return $this->render('annonces/edit.html.twig', [
            'annonces' => $annonce,
            
        ]);
    }

}

