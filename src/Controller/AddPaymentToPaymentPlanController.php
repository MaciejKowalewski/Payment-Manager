<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddPaymentToPaymentPlanController extends AbstractController
{
    #[Route('/add/paymenttopaymentplan', name: 'app_add_payment_to_payment_plan')]
    public function index(): Response
    {
        return $this->render('add_payment_to_payment_plan/index.html.twig', [
            
        ]);
    }
}
