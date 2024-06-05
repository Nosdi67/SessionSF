<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/Session/adminPage/categories', name: 'categories')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories,
        ]);
    }

    #[Route('/Session/adminPage/categories/new', name: 'categorie_new')]
    #[Route('/Session/adminPage/categories/{id}/edit', name: 'categorie_edit')]
    public function new_edit(CategorieRepository $categorieRepository,Categorie $categorie=null, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if($categorie==null){
            $categorie= new Categorie();
        }

        $form=$this->createForm(CategorieFormType::class,$categorie);
        $form->handleRequest($request);

        {
            if($form->isSubmitted() && $form->isValid()){
                $categorie = $form->getData();
                $entityManagerInterface->persist($categorie);
                $entityManagerInterface->flush();

                return $this->redirectToRoute('categories');
            }
        }
        return $this->render('categorie/new.html.twig', [
            'controller_name' => 'CategorieController',
            'categorieForm' => $form,
            'edit'=>$categorie->getId(),
            
        ]);
    }

    #[Route('/Session/adminPage/categories/{id}/delete', name: 'categorie_delete')]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($categorie);
        $entityManagerInterface->flush();
        
        return $this->redirectToRoute('categories');
    }
}
