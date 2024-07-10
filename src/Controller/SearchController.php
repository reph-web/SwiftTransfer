<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(UserRepository $userRepo, PaginatorInterface $paginator, Request $request): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $searchQuery = $request->query->get('q', '');

        $query = $userRepo->findUser($searchQuery); //query sent

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page number (init by 1)
            10 // limit per page
        );
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'pagination' => $pagination,
            'searchQuery' => $searchQuery,

        ]);
    }
}
