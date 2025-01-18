<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentPlan;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PaymentsRepository;
use App\Entity\Payments;
use App\Form\PaymentPlanFormType;

class PaymentPlanController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PaymentsRepository $paymentsRepository,
    )
    {}


    #[Route('/paymentplan', name: 'app_payment_plan')]
    public function index(Request $request): Response
    {
        $paymentId = $request->getSession()->get('PaymentId');
        $payment = new Payments();
        $payment = $this->paymentsRepository->findBy(['id' => $paymentId])[0];
        $paymentPlan = new PaymentPlan();
        $paymentPlan->setAmount($payment->getAmount());
        $form = $this->createForm(PaymentPlanFormType::class, $paymentPlan);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $paymentPlan->setPayments($payment);
            $payment->setPaymentType('paymentPlan');
            $this->entityManager->persist($payment);
            $this->entityManager->persist($paymentPlan);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_add_payment_to_payment_plan');
        }

        return $this->render('payment_plan/index.html.twig', [
            'AddPaymentPlan' => $form,
        ]);
    }
}
