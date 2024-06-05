<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Form\ModulesFormType;
use App\Repository\ModulesRepository;
use Symfony\Component\Filesystem\Path;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModulesController extends AbstractController
{
    #[Route('/Session/adminPage/modules', name: 'modules')]
    public function index(ModulesRepository $modulesRepository): Response
    {
        $modules= $modulesRepository->findAll();
        return $this->render('modules/index.html.twig', [
            'controller_name' => 'ModulesController',
            'modules' => $modules,
        ]);
    }

    
    #[Route('/Session/adminPage/modules/new', name:'modules_new')]
    #[Route('/Session/adminPage/modules/{id}/edit', name:'modules_edit')]
    public function new_edit(Modules $module = null , EntityManagerInterface $entityManagerInterface, Request $request):Response
    {
        if(!$module){
            $module = new Modules();
        }
        $form=$this->createForm(ModulesFormType::class,$module);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $module=$form->getData();
            $entityManagerInterface->persist($module);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('modules');
        }

        return $this->render('modules/new.html.twig',[
            'controller_name' => 'ModulesController',
            'module' => $module,
            'edit' => $module->getId(),
            'moduleForm'=>$form,
        ]);
        
    }

    #[Route('/Session/adminPage/modules/{id}/info', name:'modules_info')]
    public function info_module(Modules $module):Response
    {
        return $this->render('/modules/info.html.twig',[
            'controller_name' => 'ModulesController',
           'module' => $module,
        ]);
    }

    #[Route('/Session/adminPage/modules/{id}/delete', name:'modules_delete')]
    public function delete_module(Modules $module, EntityManagerInterface $entityManagerInterface):Response

    {
        $entityManagerInterface->remove($module);
        $entityManagerInterface->flush();
    
        return $this->redirectToRoute('modules');
    }
}
