<?php

namespace App\Controller;

use App\HttpClient\ApiHttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/Session/adminPage/Users', name: 'api')]
    public function index(ApiHttpClient $apiHttpClient): Response
    {
        $users=$apiHttpClient->getUsers();
        return $this->render('api/index.html.twig', [
            'users'=>$users
        ]);
    }
}
