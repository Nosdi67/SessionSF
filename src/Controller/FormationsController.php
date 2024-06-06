<?php

namespace App\Controller;

use App\Entity\Formations;
use App\Form\FormationFormType;
use App\Repository\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
        $programmes=$formation->getProgrammes();
        return $this->render('/formations/info.html.twig',[
            'controller_name' => 'FormationsController',
            'formation' => $formation,
            'stagiaires' => $stagiaires,
            'programmes' => $programmes,

        ]);
    }

    #[Route('Session/adminPage/formations/add', name: 'formation_new')]
    #[Route('/Session/adminPage/formations/{id}/edit', name: 'formation_edit')]
    public function new_edit(Formations $formation = null, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if(!$formation){
            $formation = new Formations();
        }
        $form=$this->createForm((FormationFormType::class),$formation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $formation=$form->getData();
            $entityManagerInterface->persist($formation);
            $entityManagerInterface->flush();
            $this->addFlash('success','Formation ajoutée avec succès');
           
            return $this->redirectToRoute('formations');
        }
        return $this->render('formations/new.html.twig',[
            'controller_name' => 'FormationsController',
            'formation' => $formation,
            'edit' => $formation->getId(),
            'formationForm'=>$form,
        ]);

    }
}
