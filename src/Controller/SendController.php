<?php

namespace App\Controller;

use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class SendController extends AbstractController
{
    #[Route('/send', name: 'app_send')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepo): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        /**
        * @var User
        */
        $user = $this->getUser();

        $transaction = new Transaction();
        $transactionForm = $this->createForm(TransactionType::class, $transaction);
        $transactionForm->handleRequest($request);
        if ($transactionForm->isSubmitted() && $transactionForm->isValid()) {

            // Get data from the form
            $sender = $userRepo->find($user->getId());
            $receiver = $transaction->getReceiver();
            $amount = $transaction->getAmount();
            
            // Create a new form
            $transaction->setSender($sender);
            $transaction->setCreatedAt(new \DateTimeImmutable());
            
            // Persist in db the transaction
            $entityManager->persist($transaction);
            $entityManager->flush();

            // Change the balance of receiver and sender and add transc to related groups
            $sender->setBalance($sender->getBalance() - $amount);
            $receiver->setBalance($receiver->getBalance() + $amount);

            // Create notification
            $sender_notif = new Notification;
            $sender_notif->setUser($sender);
            $sender_notif->setType('transaction');
            $sender_notif->setContent('You sent $'.$amount.' to '.$receiver->getDisplayedName());
            $sender_notif->setRead(false);


            $receiver_notif = new Notification;
            $receiver_notif->setUser($receiver);
            $receiver_notif->setType('transaction');
            $receiver_notif->setContent('You received $'.$amount.' from '.$sender->getDisplayedName());
            $receiver_notif->setRead(false);

            // Persist in db
            $entityManager->persist($sender);
            $entityManager->persist($sender_notif);
            $entityManager->persist($receiver);
            $entityManager->persist($receiver_notif);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_send');
        }

        return $this->render('send/index.html.twig', [
            'controller_name' => 'SendController',
            'form' => $transactionForm->createView(),
            'balance' =>  $user->getBalance(),
            'contacts' => $user->getContact(), // Sent to twig, for special page if user dont have contacts
        ]);
    }
}
