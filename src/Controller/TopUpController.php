<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TopUpController extends AbstractController
{
    #[Route('/top-up', name: 'app_topUp')]
    public function index(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('top_up/index.html.twig', [
            'controller_name' => 'TopUpController',
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);
    }
}
