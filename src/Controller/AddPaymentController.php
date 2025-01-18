<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Payments;
use App\Form\AddPaymentType;
use Symfony\Bundle\SecurityBundle\Security;

class AddPaymentController extends AbstractController
{
    public function __construct(
        private Security $security,
    )
    {}

    #[Route('/addpayment', name: 'app_add_payment')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $loggedUser = $this->security->getUser();
        $payment = new Payments();
        $form = $this->createForm(AddPaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment->setEmail($loggedUser);
            $payment->setAmount(sprintf('%.2f',$payment->getAmount()));
            $payment->setPaid(False);
            $entityManager->persist($payment);
            $entityManager->flush();

            if($payment->getPaymentType()==='cyclic'){
                $session = $request->getSession();
                $session->set('PaymentId', $payment->getId());
                $payment->setPaymentType('one-time');
                $entityManager->persist($payment);
                $entityManager->flush();
                return $this->redirectToRoute('app_set_payment_cycle');
            }
            elseif($payment->getPaymentType()==='paymentPlan'){
                $session = $request->getSession();
                $session->set('PaymentId', $payment->getId());
                $payment->setPaymentType('one-time');
                $entityManager->persist($payment);
                $entityManager->flush();
                return $this->redirectToRoute('app_payment_plan');
            }
            else{
                return $this->redirectToRoute('app_payments');
        }}

        return $this->render('add_payment/index.html.twig', [
            'AddPaymentType' => $form,
        ]);
    }
}