<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PaymentUserRepository;
use App\Repository\PaymentsRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SelectYearFormType;
use App\Service\PaymentsProvider;

class PaymentsController extends AbstractController
{
    public function __construct(
        private PaymentUserRepository $paymentUserRepository,
        private Security $security,
        private PaymentsRepository $paymentsRepository,
        private PaymentsProvider $paymentsProvider,
    )
    {}

    #[Route('/payments', name: 'app_payments')]
    public function index(Request $request): Response
    {
        if($request->getSession()->get('selectedYear')){
            $selectedYear = $request->getSession()->get('selectedYear');
        }else{
            $selectedYear = date('Y');
        }
        $form = $this->createForm(SelectYearFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
            if($form->get('previousYear')->isClicked()){
                $session->set('selectedYear', $selectedYear-1);
            }elseif($form->get('nextYear')->isClicked()){
                $session->set('selectedYear', $selectedYear+1);
            }
            return $this->redirectToRoute('app_payments');
        }

        $loggedUser = $this->security->getUser();
        $selectedPayments = [];

        if($loggedUser){
            $paymentsArray = $this->paymentsRepository->findBy(['email' => $loggedUser, 'isPaid'=> 'f']);
            $selectedPayments = $this->paymentsProvider->transformDataForTwig($paymentsArray, $selectedYear);
        }else{
            return $this->redirectToRoute('app_login');
        }
        return $this->render('payments/index.html.twig', [
            'controller_name' => 'PaymentsController',
            'payments' => $selectedPayments,
            'SelectYear' => $form,
            'SelectedYear' => $selectedYear,
        ]);
    }
}
