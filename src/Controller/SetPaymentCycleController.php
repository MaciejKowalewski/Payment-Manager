<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\CyclicPayment;
use App\Entity\Payments;
use App\Form\CyclicPaymentFormType;
use App\Repository\CyclicPaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PaymentsRepository;

class SetPaymentCycleController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private PaymentsRepository $paymentsRepository,
        private CyclicPaymentRepository $cyclicPaymentRepository,
    )
    {}
    
    #[Route('/setpaymentcycle', name: 'app_set_payment_cycle')]
    public function index(Request $request): Response
    {
        $paymentId = $request->getSession()->get('PaymentId');
        $payment = new Payments();
        $payment = $this->paymentsRepository->findBy(['id' => $paymentId])[0];
        $cyclicPayment = new CyclicPayment();
        $form = $this->createForm(CyclicPaymentFormType::class, $cyclicPayment);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $cyclicPayment->setPayment($payment);
            if($cyclicPayment->getDays() === null && $cyclicPayment->getMonths() === null && $cyclicPayment->getYears() === null){
                $payment->setPaymentType('one-time');
            }else{
                $payment->setPaymentType('cyclic');
                $this->entityManager->persist($cyclicPayment);
            }
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_payments');
        }

        return $this->render('set_payment_cycle/index.html.twig', [
            'AddPaymentCycle' => $form,
        ]);
    }
}
