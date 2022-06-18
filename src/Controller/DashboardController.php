<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Mouvement;
use App\Entity\MouvementItem;
use App\Entity\Prix;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

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
    public function caisse(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod("post")) {
            $user = $this->getUser();

            $produits = $request->request->all('produit');
            $quantite = $request->request->all('quantite');
            $prixId = $request->request->all('prixId');
            $total = $request->request->all('total');
            $grandTotal = $request->request->get('grandTotal');
            $montantRemis = $request->request->get('montantRemis');
            $montantRendu = $request->request->get('montantRendu');

            $mouvement = new Mouvement();
            $mouvement->setDateMouvement(new \Datetime());
            $mouvement->setMontantTotal($grandTotal);
            $mouvement->setMontantRemis($montantRemis);
            $mouvement->setMontantRendu($montantRendu);
            $mouvement->setCaissier($user);
            $mouvement->setIsVente(true);
            $entityManager->persist($mouvement);
            $sousTotal = 0;

            foreach ($produits as $key => $produitId) {
                $mouvementItem = new MouvementItem();
                $mouvementItem->setMouvement($mouvement);
                $mouvementItem->setProduit($entityManager->getReference(Produit::class, $produitId));
                $mouvementItem->setNombre($quantite[$key]);
                $mouvementItem->setTotal($total[$key]);
                $mouvementItem->setPrixUT($entityManager->getReference(Prix::class, $prixId[$key]));
                $entityManager->persist($mouvementItem);
                $sousTotal += $total[$key];
            }

            if ($sousTotal != $grandTotal) {
                //raise error
            }
            $entityManager->flush();
            return $this->redirectToRoute('dashboard');
        }

        $form = new CaisseType();
        return $this->renderForm('dashboard/caisse.html.twig', [
            'form' => $form,
        ]);
    }
}
