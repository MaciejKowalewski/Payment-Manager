<?php

namespace App\Service;

use App\Repository\PaymentsRepository;

class PaymentsProvider{

    public function __construct(
        private PaymentsRepository $paymentsRepository,
    )
    {}

    public function transformDataForTwig(array $paymentsArray, $selectedYear): array {
            usort($paymentsArray, function($a, $b) {
                return strcmp($a->getPaymentDate()->format('Y-m-d'), $b->getPaymentDate()->format('Y-m-d'));
            });

            $paymentsWithVirtualsArray = $this->getVirtualPayments($paymentsArray, $selectedYear);
            $selectedPayments = [];
            $selectedPayments['overdue']['totalAmount'] = 0;
            foreach($paymentsWithVirtualsArray as $payment){
                if($payment->getPaymentDate()->format('y-m-d')<date('y-m-d')){
                    $selectedPayments['overdue']['totalAmount'] += $payment->getAmount();
                    $selectedPayments['overdue'][] = $payment;
                }elseif($payment->getPaymentDate()->format('Y') === date($selectedYear)){
                    $monthNum = $payment->getPaymentDate()->format('m');
                    if(!isset($selectedPayments[$payment->getPaymentDate()->format('Y')][date('F', mktime(0, 0, 0, $monthNum, 10))]['totalAmount'])){
                        $selectedPayments[$payment->getPaymentDate()->format('Y')][date('F', mktime(0, 0, 0, $monthNum, 10))]['totalAmount'] = 0;
                    }
                    $selectedPayments[$payment->getPaymentDate()->format('Y')][date('F', mktime(0, 0, 0, $monthNum, 10))]['totalAmount'] += $payment->getAmount();
                    $selectedPayments[$payment->getPaymentDate()->format('Y')][date('F', mktime(0, 0, 0, $monthNum, 10))][] = $payment;
                }
            }
            ksort($selectedPayments,1);
            return $selectedPayments;
    }

    public function getVirtualPayments(array $paymentsArray, $selectedYear): array{
        foreach($paymentsArray as $payment){
            dd($payment->getCyclicPayments()->getDays());
            if(!$payment->isPaid()){
                if($payment->getPaymentType()==='cyclic'){
                    $virtualPayment = clone $payment;
                    while($virtualPayment->getPaymentDate()->format('Y') < date($selectedYear+2)){
                        $date = new \DateTimeImmutable($virtualPayment->getPaymentDate()->format('Y-m-d'));
                        $virtualPayment = clone $payment;
                        $day = intval($payment->getCyclicPayments()->first()->getDays());
                        $month = intval($payment->getCyclicPayments()->first()->getMonths());
                        $year = intval($payment->getCyclicPayments()->first()->getYears());
                        $virtualPayment->setPaymentDate($date->modify('+'.$year.' year, +'.$month.' month, +'.$day.' day'));
                        array_push($paymentsArray,$virtualPayment);
                    } 
                }
                elseif($payment->getPaymentType()==='paymentPlan'){
                    $virtualPayment = clone $payment;
                    foreach($virtualPayment->getPaymentPlan() as $Planpayment){
                        $date = $Planpayment->getPaymentDate();
                        $Planpayment = clone $payment;
                        $Planpayment->setPaymentDate($date);
                        array_push($paymentsArray,$Planpayment);
                    }
                }
            }
        }
        return $paymentsArray;
    }

}