<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\AddContactType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(UserRepository $repo, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        
        /**
        * @var User
        */
        $user = $this->getUser();
        $contacts = $repo->find($user->getId())->getContact();
        
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contacts' => $contacts,
        ]);
    }
    
}
