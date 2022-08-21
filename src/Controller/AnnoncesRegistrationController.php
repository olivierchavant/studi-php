<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\ProfilRecruteur;
use App\Form\AnnoncesType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesRegistrationController extends AbstractController
{
    #[Route('/annonces/registration', name: 'app_annonces_registration')]
    public function index(Request $request, EntityManagerInterface $em, int $id = null): Response
    {
        $annonce = new Annonces(); 
        $identifier = $this->getUser(); 
        $ProfilId = $identifier->getProfilRecruteur()->getId(); 
        $ProfilRecruteur = $em->getRepository(ProfilRecruteur::class)->findBy([ 'id'  => $ProfilId])[0] ; 
        $annonce->setProfilRecruteur( $ProfilRecruteur); 
        $annonce->setDatePublication(new DateTime('now'));
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $em->persist($annonce); 
            $em->persist($ProfilRecruteur); 
            $em->flush(); 
            return $this->redirectToRoute('app_users');
        }; 
          $parameters = array(
            'registrationForm'      => $form->createView() 
        );
      

        return $this->render('annonces_registration/index.html.twig', $parameters);
       
    }
}