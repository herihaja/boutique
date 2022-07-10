<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProduitService;

use App\Form\CaisseType;
use App\Repository\ProduitRepository;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(ProduitService $service, ProduitRepository $repo): Response
    {
        $authUser = $this->getUser();
        if (!$authUser->isIsSuperuser())
            return $this->forward('App\Controller\DashboardController::caisse', [
                'color' => 'green',
            ]);
        else
            $redirectUrl = 'dashboard';
        
        $frequenceVente = $repo->getFrequenceVente();
        
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'frequenceVente' => $frequenceVente
        ]);
    }


    #[Route('/caisse', name: 'caisse')]
    public function caisse(Request $request, ProduitService $service): Response
    {
        if ($request->isMethod("post")) {
            $user = $this->getUser();

            $service->handlePostDataCaisse($request->request, $user);

            return $this->redirectToRoute('dashboard');
        }

        $form = new CaisseType();
        return $this->renderForm('dashboard/caisse.html.twig', [
            'form' => $form,
        ]);
    }
}
