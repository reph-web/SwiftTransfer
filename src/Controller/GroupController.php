<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group')]
class GroupController extends AbstractController
{
    #[Route('/', name: 'app_group', methods: ['GET'])]
    public function index(UserRepository $repo): Response
    {
        /**
        * @var User
        */
        $user = $this->getUser();

        $groups = $repo->find($user->getId())->getGroups();
        return $this->render('group/index.html.twig', [
            'groups' => $groups,
        ]);
    }
}
