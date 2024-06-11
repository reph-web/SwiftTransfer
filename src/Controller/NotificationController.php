<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(NotificationRepository $notifications): Response
    {
        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
            'notifications' => $notifications
        ]);
    }
}
