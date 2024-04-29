<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchUpdaterController extends AbstractController
{
    #[Route('/searchUpdater/{searchedUser?}', name: 'app_searchUpdater')]
    public function index($searchedUser, UserRepository $userRepo): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $result = $userRepo->findBySearchBar($searchedUser);
        $cleanedResult = [];
        foreach($result as $user){
            $cleanedResult[] = $user->getUsername();
        }
        return $this->json(json_encode($cleanedResult));
    }
}
