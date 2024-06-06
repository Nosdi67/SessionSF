<?php

namespace App\Controller;

use App\Entity\Formations;
use App\Repository\FormationsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationsController extends AbstractController
{
    #[Route('Session/adminPage/formations', name: 'formations')]
    public function index(FormationsRepository $formationsRepository, ): Response
    {
        $formations=$formationsRepository->findAll();
        return $this->render('formations/index.html.twig', [
            'controller_name' => 'FormationsController',
            'formations' => $formations,
        ]);
    }

    #[Route('Session/adminPage/formations/info/{id}', name: 'formation_info')]
    public function info( Formations $formation): Response
    {
        $stagiaires=$formation->getStagiaires();
        return $this->render('/formations/info.html.twig',[
            'controller_name' => 'FormationsController',
            'formation' => $formation,
            'stagiaires' => $stagiaires,

        ]);
    }
}
