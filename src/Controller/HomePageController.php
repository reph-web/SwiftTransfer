<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        /**
         * @var User
         */
        $user = $this->getUser();

        $transactionsSendedArray = $user->getTransactionSended()->toArray();
        $transactionsReceivedArray = $user->getTransactionsReceived()->toArray();
        $mergedTransactions = array_merge($transactionsSendedArray, $transactionsReceivedArray);
        usort($mergedTransactions, function ($a, $b) {
            return $a->getCreatedAt() <=> $b->getCreatedAt();
        });

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'transactions' => $mergedTransactions,
        ]);
    }
}
