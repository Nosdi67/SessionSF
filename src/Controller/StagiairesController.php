<?php

namespace App\Controller;

use App\Entity\Stagiaires;
use App\Form\StagiairesFormType;
use App\Repository\StagiairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiairesController extends AbstractController
{
    #[Route('/stagiaires', name: 'stagiaires')]
    public function index(StagiairesRepository $stagiairesRepository): Response
    {
        $stagiaires=$stagiairesRepository->findAll();
        return $this->render('stagiaires/index.html.twig', [
            'controller_name' => 'StagiairesController',
            'stagiaires' => $stagiaires,
        ]);
    }
    #[Route('/stagiaires/new', name:'stagiaire_new')]
    #[Route('/stagiaires/{id}/edit', name:'stagiaire_edit')]
    public function new_edit(Stagiaires $stagiaire=null, EntityManagerInterface $entityManagerInterface,Request $request): Response
    {
       if(!$stagiaire){
        $stagiaire= new Stagiaires();
       } 
       $form=$this->createForm(StagiairesFormType::class,$stagiaire);
       $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()){
            $stagiaire=$form->getData();
            $entityManagerInterface->persist($stagiaire);
            $entityManagerInterface->flush();
        
            return $this->redirectToRoute('stagiaires');
        }
        return $this->render('stagiaires/new.html.twig',[
            'controller_name' => 'StagiairesController',
            'stagiaire' => $stagiaire,
            'edit' => $stagiaire->getId(),
            'StagiairesForm' => $form,
        ]);

    }
    #[Route('/stagiaires/info/{id}', name:'stagiaire_info')]
    public function info(Stagiaires $stagiaire): Response
    {
        return $this->render('stagiaires/info.html.twig',[
            'controller_name' => 'StagiairesController',
            'stagiaire' => $stagiaire,
        ]);
    }

    #[Route('/stagiaires/{id}/delete', name:'stagiaire_delete')]
    public function delete(Stagiaires $stagiaire, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($stagiaire);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('stagiaires');
    }
}
