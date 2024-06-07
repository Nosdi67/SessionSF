<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\Formations;
use App\Entity\Modules;
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
        $nonProgrammes=$sr->findProgrammesNonInscrits($formation->getId());


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
            'nonProgrammes' => $nonProgrammes,

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
   
    
    
    #[Route('Session/adminPage/formations/info/addStagiaire/{formation}/{stagiaire}', name: 'formation_add_stagiaire')]
    public function addStagiaire(Formations $formation, Stagiaires $stagiaire, EntityManagerInterface $entityManagerInterface): Response
    {
        if(count($formation->getStagiaires()) >= $formation->getNbPlace()){//si le nombre de stagiaire dépasse le nombre de place
         
            $this->addFlash('danger','Impossible d\'ajouter ce stagiaire, la formation est pleine');
            return $this->redirectToRoute('formation_info', ['id' => $formation->getId(),]);
        }else{
        
            $formation->addStagiaire($stagiaire);//on l'ajoute à la formation
            $entityManagerInterface->persist($formation);
            $entityManagerInterface->flush();
            $this->addFlash('success','Stagiaire ajouté avec succès');
            return $this->redirectToRoute('formation_info', ['id' => $formation->getId(),]);
        }
    }
    #[Route('Session/adminPage/formations/info/removeStagiaire/{formation}/{stagiaire}', name: 'formation_remove_stagiaire')]
    public function removeStagiaire(Formations $formation, Stagiaires $stagiaire,EntityManagerInterface $entityManagerInterface ): Response
    {
        $formation->removeStagiaire($stagiaire);
        $entityManagerInterface->persist($formation);
        $entityManagerInterface->flush();
        $this->addFlash('success','Stagiaire supprimé avec succès');
        return $this->redirectToRoute('formation_info', ['id' => $formation->getId(),]);
    }

    #[Route('Session/adminPage/formations/info/addProgramme/{formation}/{module}', name: 'formation_add_programme')]
    public function addProgramme(Formations $formation, Modules $module, EntityManagerInterface $entityManagerInterface): Response
    {
        $nbJour=filter_input(INPUT_POST, 'nbJours', FILTER_SANITIZE_NUMBER_INT);

        if($nbJour == null){
            $this->addFlash('danger','Veuillez renseigner le nombre de jour');
            return $this->redirectToRoute('formation_info', ['id' => $formation->getId(),]);
        }

        $programme = new Programme();
        $programme->setNbJour($nbJour);
        $programme->setFormation($formation);
        $programme->setModule($module);

        $formation->addProgramme($programme);
        $entityManagerInterface->persist($formation);
        $entityManagerInterface->flush();
        $this->addFlash('success','Programme ajouté avec succès');
        return $this->redirectToRoute('formation_info', ['id' => $formation->getId(),]);
    }

    #[Route('/Session/adminPage/formations/info/removeProgramme/{formation}/{programme}', name: 'formation_remove_programme')]
    public function removeProgramme(Formations $formation, Programme $programme, EntityManagerInterface $entityManagerInterface): Response
    {
        $formation->removeProgramme($programme);
        $entityManagerInterface->persist($formation);
        $entityManagerInterface->flush();
        $this->addFlash('success','Programme supprimé avec succès');
        return $this->redirectToRoute('formation_info', ['id' => $formation->getId(),]);
    }
}
