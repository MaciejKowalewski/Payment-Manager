<?php

namespace App\Controller;


use App\Form\AddPaymentType;
use App\Repository\PaidRepository;
use App\Repository\PaymentPlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PaymentUserRepository;
use App\Repository\PaymentsRepository;
use App\Service\PaymentProvider;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    public function __construct(
        private PaymentUserRepository $paymentUserRepository,
        private Security $security,
        private PaymentsRepository $paymentsRepository,
        private PaymentPlanRepository $paymentPlanRepository,
        private EntityManagerInterface $entityManager,
        private PaidRepository $paidRepository,
        private PaymentProvider $paymentProvider
    )
    {}

    #[Route('/payment/{index}', name: 'app_payment')]
    public function index($index, Request $request): Response
    {
        $loggedUser = $this->security->getUser();
        $loggedUserId = $loggedUser->getId();
        $loggedUserPayment = $this->paymentsRepository->findBy(['id' => $index])[0];
        $loggedUserPaymentId = $loggedUserPayment->getEmail()->getId();

        if($loggedUserId === $loggedUserPaymentId){
            $payment = $this->paymentsRepository->findOneBy(['id' => $index]);

            $form = $this->createForm(AddPaymentType::class, $payment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->paymentProvider->transformDataForTwig($payment, $loggedUser);
            }
        }
        
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentsController',
            'payment' => $payment,
            'form' => $form->createView(),
            'title' => $payment->getPaymentName(),
        ]);
    }

    #[Route('/payment/delete/{index}', name: 'delete_payment')]
    public function delete($index): Response
    {
        $loggedUser = $this->security->getUser();
        $loggedUserId = $loggedUser->getId();
        $loggedUserPayment = $this->paymentsRepository->findBy(['id' => $index])[0];
        $loggedUserPaymentId = $loggedUserPayment->getEmail()->getId();

        if($loggedUserId === $loggedUserPaymentId){
                if($loggedUserPayment->getPaymentType() === 'paymentPlan'){
                    $virtualPayments = $loggedUserPayment->getPaymentPlan();
                    foreach($virtualPayments as $singlePaymentPlan){
                        $loggedUserPayment->removePaymentPlan($singlePaymentPlan);
                    }
                }elseif($loggedUserPayment->getPaymentType() === 'cyclic'){
                    $virtualPayments = $loggedUserPayment->getCyclicPayments();
                    foreach($virtualPayments as $payment){
                        $loggedUserPayment->removeCyclicPayment($payment);
                    }
                }
                $this->entityManager->remove($loggedUserPayment);
                $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_payments');
    }
}
