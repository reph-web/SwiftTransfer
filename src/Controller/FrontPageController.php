<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontPageController extends AbstractController
{
    #[Route('/frontPage', name: 'app_frontPage')]
    public function index(): Response
    {
        return $this->render('frontPage/index.html.twig', [
            'controller_name' => 'FrontPageController',
        ]);
    }
}
