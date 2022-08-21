<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilRecruteurController extends AbstractController
{
    #[Route('/profil/recruteur', name: 'app_profil_recruteur')]
    public function index(): Response
    {
        return $this->render('profil_recruteur/index.html.twig', [
            'controller_name' => 'ProfilRecruteurController',
        ]);
    }
}
