<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilCandidatController extends AbstractController
{
    #[Route('/profil/candidat', name: 'app_profil_candidat')]
    public function index(): Response
    {
        return $this->render('profil_candidat/index.html.twig', [
            'controller_name' => 'ProfilCandidatController',
        ]);
    }
}
