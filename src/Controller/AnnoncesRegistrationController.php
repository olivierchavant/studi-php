<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesRegistrationController extends AbstractController
{
    #[Route('/annonces/registration', name: 'app_annonces_registration')]
    public function index(): Response
    {
        return $this->render('annonces_registration/index.html.twig', [
            'controller_name' => 'AnnoncesRegistrationController',
        ]);
    }
}
