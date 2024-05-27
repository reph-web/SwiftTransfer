<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{username}', name: 'app_profile')]
    public function index($username, UserRepository $userRepo): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_frontPage');
        }
        $user = $userRepo->findOneBy(['username' => $username]);

        if (!$user) {
            throw $this->createNotFoundException('No user found');
        }

        // Check if the user profile is in contactLsInContactListist or is the user logged
        $isInContactList = null;

        /**
        * @var User
        */
        $userLogged = $this->getUser();
        foreach( $userLogged->getContact() as $c){
            if($c->getUsername() === $username){
                $isInContactList = true;
            }
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'isInContactList' => $isInContactList,
        ]);
    }
}
