<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Transaction;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;

class SendController extends AbstractController
{
    #[Route('/send', name: 'app_send')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transaction = new Transaction();

        $transactionForm = $this->createForm(TransactionType::class, $transaction);
        $transactionForm->handleRequest($request);
        if ($transactionForm->isSubmitted() && $transactionForm->isValid()) {
            $sender = $this->getUser();
            $receiver = $transaction->getReceiver();
            $amount = $transaction->getAmount();
            $group = $transaction->getRelatedGroup();
            
            $transaction->setSender($sender);
            $transaction->setCreatedAt(new \DateTimeImmutable());
            
            $entityManager->persist($transaction);
            $entityManager->flush();

            $sender->setBalance($sender->getBalance() - $amount);
            $receiver->setBalance($receiver->getBalance() + $amount);
            foreach($group as $g){
                $g->addTransaction($transaction);
            }
            $entityManager->persist($sender);
            $entityManager->persist($receiver);
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('notice', "$amount sent to {$receiver->getUsername()} ");
            return $this->redirectToRoute('app_send');
        }

        return $this->render('send/index.html.twig', [
            'controller_name' => 'SendController',
        ]);
    }
}
