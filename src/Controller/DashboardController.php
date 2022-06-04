<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\CaisseType;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        $authUser = $this->getUser();
        if ($authUser->isIsSuperuser())
            return $this->forward('App\Controller\DashboardController::caisse', [
                'color' => 'green',
            ]);
        else
            $redirectUrl = 'dashboard';

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }


    #[Route('/caisse', name: 'caisse')]
    public function caisse(): Response
    {
        $form = new CaisseType();
        return $this->renderForm('dashboard/caisse.html.twig', [
            'form' => $form,
        ]);
    }
}
