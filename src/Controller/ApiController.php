<?php

namespace App\Controller;

use App\Entity\Membre;
use App\HttpClient\ApiHttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/Session/adminPage/Users', name: 'list_users')]
    public function index(ApiHttpClient $apiHttpClient): Response
    {
        $users=$apiHttpClient->getUsers();
        return $this->render('api/index.html.twig', [
            'users'=>$users
        ]);
    }
    #[Route('/Session/adminPage/Users/add-membre', name: 'membre_add', methods:'POST')]
    public function addMembre(EntityManagerInterface $entityManagerInterface, Request $request, Membre $membre = null): Response
    {
        $membre = New Membre();

        $title=filter_input(INPUT_POST,'title',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last=filter_input(INPUT_POST,'last',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $first=filter_input(INPUT_POST,'first',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone=filter_input(INPUT_POST,'phone',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $picture=filter_input(INPUT_POST,'picture',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $streetNumber=filter_input(INPUT_POST,'streetnumber',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $streetName=filter_input(INPUT_POST,'streetname',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $postCode=filter_input(INPUT_POST, 'postcode',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $city=filter_input(INPUT_POST, 'city',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $country=filter_input(INPUT_POST, 'country',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // dd($title, $last, $first, $email, $phone, $picture, $streetNumber, $streetName, $postCode, $city, $country);
        if($title && $last && $first && $email && $phone && $picture && $streetNumber && $streetName && $postCode && $city && $country){
            $membre->setTitle($title);
            $membre->setLast($last);
            $membre->setFirst($first);
            $membre->setEmail($email);
            $membre->setPhone($phone);
            $membre->setPicture($picture);
            $membre->setStreetNumber($streetNumber);
            $membre->setStreetName($streetName);
            $membre->setPostCode($postCode);
            $membre->setCity($city);
            $membre->setCountry($country);
            $entityManagerInterface->persist($membre);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('list_users');
            }else{
            // dd('error',$membre);
            return $this->redirectToRoute('list_users');
            }
    }
}
