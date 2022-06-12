<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Achat;
use App\Entity\AchatItem;
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

            $achat = new Achat();
            $achat->setDateAchat(new \Datetime());
            $achat->setMontantTotal($grandTotal);
            $achat->setMontantRemis($montantRemis);
            $achat->setMontantRendu($montantRendu);
            $achat->setCaissier($user);
            $entityManager->persist($achat);
            $sousTotal = 0;

            foreach ($produits as $key => $produitId) {
                $achatItem = new AchatItem();
                $achatItem->setAchat($achat);
                $achatItem->setProduit($entityManager->getReference(Produit::class, $produitId));
                $achatItem->setNombre($quantite[$key]);
                $achatItem->setTotal($total[$key]);
                $achatItem->setPrixUT($entityManager->getReference(Prix::class, $prixId[$key]));
                $entityManager->persist($achatItem);
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
