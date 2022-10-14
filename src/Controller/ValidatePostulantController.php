<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\CandidatsAnnonces;
use App\Entity\ProfilCandidat;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class ValidatePostulantController extends AbstractController
{
   

    #[Route('/postulantAValider', name: 'app_postulant_AV')]
    // liste des candidatures à valider 
    public function aValider(EntityManagerInterface $em): Response
    {
        $postulant = $em->getRepository(CandidatsAnnonces::class)->findBy(['validé' => false]);
        return $this->render('postulants/index.html.twig', [
            'postulants' => $postulant 
        ]);
    }

    #[Route('/annoncesPostulant/{annonceId} {candidatId}', name: 'app_annonces_postulant')]

    // récupération des informations d'une candidature ,  Profil candidat et annonces 

    public function annoncesPostulant(EntityManagerInterface $em, int $annonceId=null ,int $candidatId=null ): Response
    {

        $association = $em->getRepository(CandidatsAnnonces::class)->findBy(['profilCandidat' => $candidatId , 'annonces' => $annonceId])[0]; 
        // dd($association);
        $annonce = $em->getRepository(Annonces::class)->findBy(['id' => $annonceId ])[0];
        $postulant = $em->getRepository(ProfilCandidat::class)->findBy(['id' => $candidatId])[0]; 

         // dd($annonce, $postulant); 

        return $this->render('postulants/edit.html.twig', [
            'candidat' => $postulant , 
            'annonce' => $annonce, 
            'association' => $association
        ]);
    }

    #[Route('/annonces/annoncePostulantValider/{id}', name: 'app_annonces_PV')]

    // Validation d'une candidature par le consultant et envoi d'un mail au recruteur avec CV candidat joint

    public function validatedPostulant(MailerInterface $mailer, EntityManagerInterface $em, int $id = null): Response

    {
        if($id) {
            
           
            $association = $em->getRepository(CandidatsAnnonces::class)->find(['id' => $id]);
            $recruteur = $association->getAnnonces()->getProfilRecruteur()->getId();
            // recherche adresse expéditeur
            $from = $this->getUser()->getEmail(); 
           
            $toId = $association->getProfilCandidat()->getcv();
            $path = $this->getParameter('cv_directory'); 
            
            
            $ToEmail = $em->getRepository(User::class)->findBy(['ProfilRecruteur' => $recruteur])[0]; 
             // recherche adresse destinataire
            $destEmail = $ToEmail->getEmail(); 
          
            // recupération du titre de l'annonce 
            $annonce = $association->getAnnonces()->getTitre();
            // validation de l'annonce 
            $association->setValidé(true); 
            $em->persist($association);  
            $em->flush(); 
            // création du mail de validation de candidature 
            $mail = (new TemplatedEmail()) // utrilisation d'un template HTML Twig pour le corps du mail 
                
            ->from($from)
            ->to($destEmail)
            ->subject('candidature Validée ' )
            ->htmlTemplate('default/mail.html.twig')
            ->attachFromPath($path.$toId)
            ->context([ 
                'titre' => $annonce ,
                'Nom' => $association->getProfilCandidat()->getNom() ,
                'Prenom' => $association->getProfilCandidat()->getPrenom(),
                'signature' => $this->getUser()->getUsername()
            ]); 

    
            $mailer->send($mail);
        };
        return $this->redirectToRoute('app_default');
       
    }

}