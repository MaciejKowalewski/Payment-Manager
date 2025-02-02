<?php

namespace App\Service;

use App\Entity\Payments;

class Month{

    private $names = ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'];

    private $payments = [];

    public function getNames(): Array{
        return $this->names;
    }

    public function getPayments(): Array{
        return $this->payments;
    }

    public function addPayment(Payments $payment){
        $this->payments[] = $payment;
    }

    public function totalAmount() : int {
        $total = 0;
        foreach($this->payments as $payment){
            $total += $payment->getAmount();
        }
        return $total;
    }

}