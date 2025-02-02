<?php

namespace App\Service;

use App\Repository\PaymentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Paid;
use App\Entity\Payments;
use App\Entity\PaymentUser;
use App\Repository\PaidRepository;
use App\Repository\PaymentPlanRepository;

class PaymentProvider{

    public function __construct(
        private PaymentsRepository $paymentsRepository,
        private EntityManagerInterface $entityManager,
        private PaidRepository $paidRepository,
        private PaymentPlanRepository $paymentPlanRepository,
    )
    {}

    public function transformDataForTwig(Payments $payment,PaymentUser $loggedUser) {
        $payment->setEmail($loggedUser);
                $payment->setAmount(sprintf('%.2f',$payment->getAmount()));
                if($payment->isPaid() === True){

                    $paid = new Paid();
                    $paid->setPaidName($payment->getPaymentName());
                    $paid->setAmount($payment->getAmount());
                    $paid->setPaidDate($payment->getPaymentDate());
                    $paid->setDescription($payment->getDescription());
                    $paid->setPaidUser($payment->getEmail());
                    $this->entityManager->persist($paid);
                    dd($paid);
                    if($payment->getPaymentType() === 'cyclic'){
                        $date = new \DateTimeImmutable($payment->getPaymentDate()->format('Y-m-d'));
                        $day = intval($payment->getCyclicPayments()->first()->getDays());
                        $month = intval($payment->getCyclicPayments()->first()->getMonths());
                        $year = intval($payment->getCyclicPayments()->first()->getYears());
                        $payment->setPaymentDate($date->modify('+'.$year.' year, +'.$month.' month, +'.$day.' day'));
                        $payment->setPaid(False);
                        $this->entityManager->persist($payment);
                    }elseif($payment->getPaymentType() === 'paymentPlan'){
                        if($payment->getPaymentPlan()->first()){
                            $payment->setPaymentDate($payment->getPaymentPlan()->first()->getPaymentDate());
                            $paymentToDel = $this->paymentPlanRepository->findOneby(['id' => $payment->getPaymentPlan()->first()->getId()]);
                            $this->entityManager->remove($paymentToDel);
                        }else{
                            $this->entityManager->remove($payment);
                        }
                        $payment->setPaid(False);
                    }else{
                        $this->entityManager->remove($payment);
                    }
                }
                $this->entityManager->flush();
                return $this->redirectToRoute('app_payments');
    }

}