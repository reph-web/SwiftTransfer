<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Profiler\FileProfilerStorage;

class FrontPageController extends AbstractController
{
    #[Route('/', name: 'app_frontPage')]
    public function index(): Response
    {
        return $this->render('frontPage/index.html.twig', [
            'controller_name' => 'FrontPageController',
        ]);
    }
}
