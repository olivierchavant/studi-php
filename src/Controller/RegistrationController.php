<?php

namespace App\Controller;

use App\Entity\ProfilCandidat;
use App\Entity\ProfilRecruteur;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\RegistrationFormConsultantType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]

    // Création d'un USER connecté et création d'un profil Candidat ou Recruteur suivant les choix de l'utilisateur 

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $ProfilCandidat = new ProfilCandidat(); 
        $ProfilRecruteur = new ProfilRecruteur(); 
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if ($user->getProfil() == "Candidat") { 
                $user->setProflCandidatid($ProfilCandidat); 
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_profil_candidat_registration' , ['id' => $ProfilCandidat->getId() ]);

                
            } elseif ($user->getProfil() == "Recruteur") {
                $user->setProfilRecruteur($ProfilRecruteur);
                $entityManager->persist($user);
                 $entityManager->flush();
                
                 return $this->redirectToRoute('app_profil_recruteur_registration', ['id' => $ProfilRecruteur->getId() ]);
                
            }
            

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/registerConsultant', name: 'app_register_C')]
    // Création d'un compte USER Consultant uniquement par ADMIN 

    public function registerConsultant(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
       
        
        $form = $this->createForm(RegistrationFormConsultantType::class, $user);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles(['ROLE_CONSULTANT']);
            $user->setProfil("consultant"); 
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
        
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_default'); 
        }

        return $this->render('registration/register_consultant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
