<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'app_api')]
class ApiController extends AbstractController
{
    #[Route('/user-search/{searchedUser?}', name: 'app_userSearch', methods: ['GET'])]
    public function userSearch($searchedUser, UserRepository $userRepo): JsonResponse
    {
        $result = $userRepo->findBySearchBar($searchedUser);
        $cleanedResult = [];
        foreach($result as $user){
            $cleanedResult[] = array(
                'username'=> $user->getUsername(),
                'displayedName' => $user->getDisplayedName(),
                'avatar' => $user->getAvatar(),
            );
        }
        return $this->json(json_encode($cleanedResult));
    }

    #[Route('/add-contact', name: 'app_addContact', methods: ['POST'])]
    public function addContact(Request $request, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
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

        // Add contact and sent to db

        $user->addContact($contact);
        $em->persist($user);
        $em->flush();
        
        return new JsonResponse(['status' => 'Contact added'], 200);
    }

    #[Route('/get-contact', name: 'app_getContact', methods: ['GET'])]
    public function getContact() : JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();

        $result = $user->getContact();
        $cleanedResult = [];
        foreach($result as $contact){

                $cleanedResult[] = array(
                    'displayedName' => $contact->getDisplayedName(),
                    'username' => $contact->getUsername(),
                    'avatar' => $contact->getAvatar(),
                );
            }
        usort($cleanedResult, function($contact1, $contact2){
            return $contact1['displayedName'] <=> $contact2['displayedName'];
        });

        return $this->json($cleanedResult);
    }

    #[Route('/remove-contact', name: 'app_removeContact', methods: ['POST'])]
    public function removeContact(Request $request, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
        // Fetch data from query
        $data = json_decode($request->getContent(), true);

        // Find contact in the repo from the id in the query
        /**
        * @var User
        */
        $user = $this->getUser();
        $contact = $userRepo->findOneBy(['username' => $data['contactUsername']]);

        // Return error if user try to add himself as contact
        if($user == $contact){
            return new JsonResponse(['error' => 'You can\'t remove yourself in contact list'], 403);
        }

        // Return error if user try to add a contact that doesn't exist
        if(!$contact){
            return new JsonResponse(['error' => 'Contact does not exist'], 404);
        }

        // Return error if user try to add a contact already in contact list
        if(!$user->getContact()->contains($contact)){
            return new JsonResponse(['error' => 'Contact is already in contact list'], 403);
        }

        // Add contact and sent to db
        $user->removeContact($contact);
        $em->persist($user);
        $em->flush();
        
        return new JsonResponse(['status' => 'Contact removed'], 200);
    }

    #[Route('/change-group-name', name: 'app_changeGroupName', methods: ['PATCH'])]
    public function changeGroupName(Request $request, GroupRepository $groupRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $group = $groupRepo->find($data['groupId']);

        if(!$group){
            return new JsonResponse(['error' => 'Group does not exist'], 404);
        }
        if($this->getUser() != $group->getOwner()){
            return new JsonResponse(['error' => 'You are not the owner'], 403);
        }
        
        $group->setName($data['value']);
        $em->persist($group);
        $em->flush();
        return new JsonResponse(['status' => 'Resource updated, new name :'.$data['value']], 200);
    }

    #[Route('/change-group-description', name: 'app_changeGroupDescription', methods: ['PATCH'])]
    public function changeDescription(Request $request, GroupRepository $groupRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $groupId = $data['groupId'];
        $group = $groupRepo->find($groupId);

        if(!$group){
            return new JsonResponse(['error' => 'Group does not exist'], 404);
        }
        if($this->getUser() != $group->getOwner()){
            return new JsonResponse(['error' => 'You are not the owner'], 403);
        }

        $group->setDescription($data['value']);
        $em->persist($group);
        $em->flush();
        return new JsonResponse(['status' => 'Resource updated, new name :'.$data['value']], 200);
    }

    #[Route('/delete-group', name: 'app_deleteGroup', methods: ['DELETE'])]
    public function deleteGroup(Request $request, GroupRepository $groupRepo, EntityManagerInterface $em): JsonResponse
    {
        $groupId = json_decode($request->getContent(), true)['groupId'];
        $group = $groupRepo->find($groupId);
        if(!$group){
            return new JsonResponse(['error' => 'Group does not exist'], 404);
        }

        if($this->getUser() != $group->getOwner()){
            return new JsonResponse(['error' => 'You are not the owner'], 403);
        }
        $em->remove($group);
        $em->flush();
        return new JsonResponse(['status' => 'Resource deleted'], 200);
    }
    
    #[Route('/invite', name: 'app_inviteInGroup', methods: ['POST'])]
    public function inviteInGroup(Request $request, GroupRepository $groupRepo, UserRepository $userRepo,EntityManagerInterface $em): JsonResponse
    {   
        $data = json_decode($request->getContent(), true);
 
        $group = $groupRepo->find($data['groupId']);
        $userInvited = $userRepo->findOneBy(array('username' => $data['invitedUser']));

        // Check if group exist
        if(!$group){
            return new JsonResponse(['error' => 'Group does not exist'], 404);
        }
        // Check if invited user exist
        if(!$userInvited){
            return new JsonResponse(['error' => 'User doesn\'t exist'], 404);
        }

        /**
        * @var User
        **/
        $user = $this->getUser();

        // Check if user has rights to invite
        if($user != $group->getOwner()){
            return new JsonResponse(['error' => 'You are not the owner'], 403);
        }

        //Check if invited is already invited


        
        // Send notification to user to ask him to join
        $notif = new Notification;
        $notif->setType('group');
        $notif->setContent($user->getDisplayedName().' has invited you to '.$group->getName());
        $notif->setIsGroupId($group->getId());
        $notif->setUser($userInvited);

        $em->persist($notif);
        $em->flush(); 

        $successMessage = 'Invite sent to '.$userInvited->getUsername().' to join '.$group->getName();
        return new JsonResponse(['status' => $successMessage], 200);
    }

    #[Route('/accept-invite', name: 'app_acceptInvite', methods: ['POST'])]
    public function acceptInvite(Request $request, GroupRepository $groupRepo,EntityManagerInterface $em): JsonResponse
    {
        /**
        * @var User
        */
        $user = $this->getUser();
        $notificationId = json_decode($request->getContent(), true)['notificationId'];


        // Search if user got an invite
        foreach($user->getNotifications() as $notif){
            if($notif->getId() == $notificationId && $notif->getType() == 'group'){
                $group = $groupRepo->find($notif->getIsGroupId());
                $group->addMember($user);
                $em->persist($group);
                $em->remove($notif);
                $em->flush();
                return new JsonResponse(['success' => 'Invite successfully accepted'], 200);
            }
        }
        return new JsonResponse(['error' => 'Invite does not exist'], 404);
    }

    #[Route('/remove-user-group', name: 'app_removeUserGroup', methods: ['DELETE'])]
    public function removeUserGroup(Request $request, GroupRepository $groupRepo, UserRepository $userRepo, EntityManagerInterface $em): JsonResponse
    {
        /**
        * @var User
        */
        $user = $this->getUser();

        //Is user has right to remove

        $groupId = json_decode($request->getContent(), true)['groupId'];
        $group = $groupRepo->find($groupId);
        if($group->getOwner() != $user){
            return new JsonResponse(['error' => 'You do not have the right to remove this user'], 403);
        }
    
        $usernameToRemove = json_decode($request->getContent(), true)['memberUsername'];
        $userToRemove = $userRepo->findOneBy(['username' => $usernameToRemove]);
        $group->removeMember($userToRemove);
        $em->persist($group);
        $em->flush();
        
        return new JsonResponse(['success' => 'User successfully removed'], 200);
        
    }

    #[Route('/decline-invite', name: 'app_declineInvite', methods: ['POST'])]
    public function declineInvite(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /**
        * @var User
        */
        $user = $this->getUser();
        $notificationId = json_decode($request->getContent(), true)['notificationId'];

        //search if user got an invite
        foreach($user->getNotifications() as $notif){
            if($notif->getId() == $notificationId && $notif->getType() == 'group'){
                
                $em->remove($notif);
                $em->flush();
                return new JsonResponse(['success' => 'Invite successfully declined'], 200);
            }
        }
        return new JsonResponse(['error' => 'Invite does not exist'], 404);
    }
}