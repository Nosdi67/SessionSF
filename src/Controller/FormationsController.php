<?php

namespace App\Controller;

use App\Entity\Formations;
use App\Entity\Stagiaires;
use App\Form\FormationFormType;
use App\Repository\FormationsRepository;
use App\Repository\StagiairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationsController extends AbstractController
{
    #[Route('Session/adminPage/formations', name: 'formations')]
    public function index(FormationsRepository $formationsRepository ): Response
    {
        $formations=$formationsRepository->findAll();
        return $this->render('formations/index.html.twig', [
            'controller_name' => 'FormationsController',
            'formations' => $formations,
        ]);
    }

    #[Route('Session/adminPage/formations/info/{id}', name: 'formation_info')]
    public function info( Formations $formation,StagiairesRepository $stagiairesRepository,FormationsRepository $sr): Response
    {
        $nonInscrits=$sr->findNonInscrits($formation->getId());


        $stagiaries=$stagiairesRepository->findAll();
        $FormationStagiaires=$formation->getStagiaires();
        $programmes=$formation->getProgrammes();
        return $this->render('/formations/info.html.twig',[
            'controller_name' => 'FormationsController',
            'formation' => $formation,
            'FormationStagiaires' => $FormationStagiaires,
            'programmes' => $programmes,
            'stagiaires' => $stagiaries,
            'nonInscrits' => $nonInscrits,

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
        $edit = $formation->getId();

        if($form->isSubmitted() && $form->isValid()){
            $formation=$form->getData();
            $entityManagerInterface->persist($formation);
            $entityManagerInterface->flush();
            if($edit){
                $this->addFlash('success','Formation modifiée avec succès');
            }else{
                $this->addFlash('success','Formation ajoutée avec succès');
            }
           
            return $this->redirectToRoute('formations');
        }
        
        return $this->render('formations/new.html.twig',[
            'controller_name' => 'FormationsController',
            'formation' => $formation,
            'edit' => $formation->getId(),
            'formationForm'=>$form,
        ]);


    }

    #[Route('Session/adminPage/formations/{id}/delete', name: 'formation_delete')]
    public function delete(Formations $formation, EntityManagerInterface $entityManagerInterface):Response
    {
        $entityManagerInterface->remove($formation);
        $entityManagerInterface->flush();
        $this->addFlash('success','Formation supprimée avec succès');
        return $this->redirectToRoute('formations');
    }
    #[Route('Session/adminPage/formations//info/{id}/addStagiaire', name: 'formation_add_stagiaire')]
    public function addStagiaire(Formations $formation, EntityManagerInterface $entityManagerInterface, StagiairesRepository $stagiairesRepository): Response
    {
        if(isset($_POST['submit'])) {
            $stagiaires = $stagiairesRepository->findAll();
            $formationStagiaires = $formation->getStagiaires();
        
            foreach ($stagiaires as $stagiaire) {
                if (!$formationStagiaires->contains($stagiaire)) {
                    $formation->addStagiaire($stagiaire);
                } else {
                    $formation->removeStagiaire($stagiaire);
                }
            }
       
        }
    
        $entityManagerInterface->persist($formation);
        $entityManagerInterface->flush();
    
        return $this->redirectToRoute('formation_edit', ['id' => $formation->getId()]);
    }
    
}
