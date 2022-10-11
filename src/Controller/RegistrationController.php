<?php

namespace App\Controller;

use App\Entity\ProfilCandidat;
use App\Entity\ProfilRecruteur;
use App\Entity\User;
use App\Form\RegistrationFormType;
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
                // return $this->render('profil_candidat_registration/index.html.twig', ['id' => $ProfilCandidat->getId() ]);

                
            } elseif ($user->getProfil() == "Recruteur") {
                $user->setProfilRecruteur($ProfilRecruteur);
                $entityManager->persist($user);
                 $entityManager->flush();
                
                 return $this->redirectToRoute('app_profil_recruteur_registration', ['id' => $ProfilRecruteur->getId() ]);
                
            }
            
            
            // do anything else you need here, like send an email

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

}
