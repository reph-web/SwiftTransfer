<?php
namespace App\Controller;

use App\Entity\Billing;
use App\Entity\Transaction;
use App\Repository\BillingRepository;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class StripeController extends AbstractController
{
   
    #[Route("/create-payment-intent", name:"create_payment_intent")]
    public function createPaymentIntent(Request $request, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        if (!$amount) {
            return new JsonResponse(['error' => 'Amount is required'], 400);
        }

        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount * 100, // amount in cents
            'currency' => 'usd',
        ]);

        $billing = new Billing();
        $billing->setStripeId($paymentIntent->id);
        $billing->setAmount($amount);
        $billing->setUser($this->getUser());
        $billing->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($billing);

        $entityManager->flush();
        return new JsonResponse(['clientSecret' => $paymentIntent->client_secret]);
    }

    
    #[Route("/payment-success", name:"payment_success")]
     
    public function paymentSuccess(Request $request, BillingRepository $billingRepository, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        if (!$amount) {
            return new JsonResponse(['error' => 'Amount is required'], 400);
        }
        
        /** 
         * @var User
        */
        $user = $this->getUser();
        $user->setBalance($user->getBalance() + $amount);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Payment successful and balance updated'], 200);
    }
}
