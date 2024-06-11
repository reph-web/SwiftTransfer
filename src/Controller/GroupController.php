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


class GroupController extends AbstractController
{
    #[Route('/group/{selectedGroupId?}', name: 'app_group', methods: ['GET'])]
    public function index($selectedGroupId, GroupRepository $groupRepo): Response
    {
        /**
        * @var User
        */
        $user = $this->getUser();
        $groups = $user->getGroups();
        
        //no arg = 1st group displayed
        if(!$selectedGroupId){
            return $this->render('group/index.html.twig', [
                'groups' => $groups,
                'firstGroupDisplayed' => $groups[0],
            ]);
        }
        
        $selectedGroup = $groupRepo->find($selectedGroupId);

        if(!$selectedGroup){
            return new Response($this->render('group/not-found.html.twig', [
            ]), 404);
        }

        if($groups->contains($selectedGroup)){
            return $this->render('group/index.html.twig', [
                'groups' => $groups,
                'firstGroupDisplayed' => $selectedGroup,
            ]);
        }else{
            return new Response($this->render('group/forbidden.html.twig', [
            ]), 403);
        }

    }

    #[Route('/create-group', name: 'app_createGroup', methods: ['GET', 'POST'])]
    public function createGroup(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        /**
        * @var User
        */
        $user = $this->getUser();

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //add creation time and owner
            $group->setCreatedAt(new \DateTimeImmutable());
            $group->setOwner($user);
            $user->addGroup($group);
            
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('app_group');
        }

        return $this->render('group/create-group.html.twig', [
            'groupForm' => $form,
        ]);
    }
}
