<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(UserRepository $repo): Response
    {
        
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'user_list' => $repo->findAll()
        ]);
    }
}
