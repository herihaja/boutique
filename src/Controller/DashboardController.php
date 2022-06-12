<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function caisse(Request $request): Response
    {
        if ($request->isMethod("post")) {
            $produits = $request->request->all('produit');
            $quantite = $request->request->all('quantite');
            $unite = $request->request->all('unite');
            $prixId = $request->request->all('prixId');
            $grandTotal = $request->request->get('grandTotal');
            $items = [];
            foreach ($produits as $key => $produitId) {
                $items[] = [
                    'produit' => $produitId, 'quantite' => $quantite[$key],
                    'unite' => $unite[$key], 'prixId' => $prixId[$key]
                ];
            }
        }
        $form = new CaisseType();
        return $this->renderForm('dashboard/caisse.html.twig', [
            'form' => $form,
        ]);
    }
}
