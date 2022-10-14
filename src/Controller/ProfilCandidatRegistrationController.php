<?php

namespace App\Controller;

use App\Entity\ProfilCandidat;
use App\Form\ProfilCandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfilCandidatRegistrationController extends AbstractController
{
    #[Route('/profil/candidat/registration/{id}', name: 'app_profil_candidat_registration')]

    // création profil candidat 
    public function index(Request $request, EntityManagerInterface $em, int $id = null, SluggerInterface $slugger): Response
    {
        
        if($id) {
            $mode = 'update';
            $profilCurrentCandidat = $em->getRepository(ProfilCandidat::class)->findBy(['id' => $id])[0];
        }

        
        $form = $this->createForm(ProfilCandidatType::class, $profilCurrentCandidat);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $cvFile = $form->get('cv')->getData();
            
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();
                try {
                    $cvFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                   
                }
                $profilCurrentCandidat->setCv($newFilename);

            }




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

