<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'app_api')]
class ApiController extends AbstractController
{
        #[Route('/searchUpdater/{searchedUser?}', name: 'app_searchUpdater', methods: ['GET'])]
        public function searchUpdater($searchedUser, UserRepository $userRepo): JsonResponse
        {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $result = $userRepo->findBySearchBar($searchedUser);
            $cleanedResult = [];
            foreach($result as $user){
                $cleanedResult[] = $user->getUsername();
            }
            return $this->json(json_encode($cleanedResult));
        }

        #[Route('/add-contact', name: 'app_addContact', methods: ['POST'])]
        public function addContact(Request $request, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
        {
            //$this->denyAccessUnlessGranted('ROLE_USER');
            //Fetch data from query
            $data = json_decode($request->getContent(), true);

            //find contact in the repo from the id in the query
            
            /**
            * @var User
            */
            $user = $this->getUser();
            $contact = $userRepo->findOneBy(['username' => $data['contactUsername']]);

            // Return error if user try to add himself as contact
            if($user == $contact){
                return new JsonResponse(['error' => 'you can\'t add yourself in contact list'], 403);
            }

            // Return error if user try to add a contact that doesn't exist
            if(!$contact){
                return new JsonResponse(['error' => 'contact doesn\'t exist'], 404);
            }

            // Return error if user try to add a contact already in contact list
            if($user->getContact()->contains($contact)){
                return new JsonResponse(['error' => 'contact already in contact list'], 403);
            }

            //add contact and sent to db

            $user->addContact($contact);
            $em->persist($user);
            $em->flush();
            
            return new JsonResponse(['status' => 'Contact added'], 200);
        }
    
        #[Route('/remove-contact', name: 'app_removeContact', methods: ['POST'])]
        public function removeContact(Request $request, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
        {
            $this->denyAccessUnlessGranted('ROLE_USER');
            //Fetch data from query
            $data = json_decode($request->getContent(), true);

            //find contact in the repo from the id in the query
            /**
            * @var User
            */
            $user = $this->getUser();
            $contact = $userRepo->findOneBy(['username' => $data['contactUsername']]);

            // Return error if user try to add himself as contact
            if($user == $contact){
                return new JsonResponse(['error' => 'you can\'t remove yourself in contact list'], 403);
            }

            // Return error if user try to add a contact that doesn't exist
            if(!$contact){
                return new JsonResponse(['error' => 'contact doesn\'t exist'], 404);
            }

            // Return error if user try to add a contact already in contact list
            if(!$user->getContact()->contains($contact)){
                return new JsonResponse(['error' => 'contact already not in contact list'], 403);
            }

            //add contact and sent to db
            $user->removeContact($contact);
            $em->persist($user);
            $em->flush();
            
            return new JsonResponse(['status' => 'Contact removed'], 200);
        }
    
        #[Route('/change-group-name/{groupId}', name: 'app_changeGroupName', methods: ['POST'])]
        public function changeGroupName($groupId, Request $request, GroupRepository $groupRepo, EntityManagerInterface $em): JsonResponse
        {
            $group = $groupRepo->find($groupId);
            if(!$group){
                return new JsonResponse(['error' => 'group doesn\'t exist'], 404);
            }

            if($this->getUser() != $group->getOwner()){
                return new JsonResponse(['error' => 'your are not the owner'], 403);
            }
            $data = json_decode($request->getContent(), true);
            $group->setName($data['name']);
            $em->persist($group);
            $em->flush();
        }

        #[Route('/change-description/{groupId}', name: 'app_changeDescription', methods: ['POST'])]
        public function changeDescription($groupId, Request $request, GroupRepository $groupRepo, EntityManagerInterface $em): JsonResponse
        {
            $group = $groupRepo->find($groupId);
            if(!$group){
                return new JsonResponse(['error' => 'group doesn\'t exist'], 404);
            }

            if($this->getUser() != $group->getOwner()){
                return new JsonResponse(['error' => 'your are not the owner'], 403);
            }
            $data = json_decode($request->getContent(), true);
            $group->setDescription($data['description']);
            $em->persist($group);
            $em->flush();
        }

        #[Route('/delete-group/{groupId}', name: 'app_delete-group', methods: ['POST'])]
        public function deleteGroup($groupId, Request $request, GroupRepository $groupRepo, EntityManagerInterface $em): JsonResponse
        {
            $group = $groupRepo->find($groupId);
            if(!$group){
                return new JsonResponse(['error' => 'group doesn\'t exist'], 404);
            }

            if($this->getUser() != $group->getOwner()){
                return new JsonResponse(['error' => 'your are not the owner'], 403);
            }
            $data = json_decode($request->getContent(), true);
            $group->setDescription($data['description']);
            $em->persist($group);
            $em->flush();
        }
        
    
}
