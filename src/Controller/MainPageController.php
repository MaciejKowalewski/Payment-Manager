<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class MainPageController extends AbstractController
{
    public function __construct(
        private Security $security,
    )
    {}

    #[Route('/', name: 'app_main_page')]
    public function index(): Response
    {
        $loggedUser = $this->security->getUser();
        if($loggedUser){
            return $this->redirectToRoute('app_payments');
        }else{
            return $this->redirectToRoute('app_login');
        }
        return $this->render('main_page/index.html.twig', [
            'controller_name' => 'MainPageController',
        ]);
    }
}
