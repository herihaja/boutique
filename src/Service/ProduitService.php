<?php 

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use App\Entity\Mouvement;
use App\Entity\MouvementItem;
use App\Entity\Stock;
use App\Entity\Produit;
use App\Entity\Prix;


class ProduitService
{
    private EntityManager $entityManager;


    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        $this->entityManager = $doctrine->getManager();
    } 


    public function handlePostDataCaisse($postData, $user)
    {
        $produits = $postData->all('produit');
        $quantite = $postData->all('quantite');
        $prixId = $postData->all('prixId');
        $total = $postData->all('total');
        $grandTotal = $postData->get('grandTotal');
        $montantRemis = $postData->get('montantRemis');
        $montantRendu = $postData->get('montantRendu');
        $isVente = true;

        $mouvement = new Mouvement();
        $mouvement->setVenteData($grandTotal, $montantRemis, $montantRendu, $user);
        $this->entityManager->persist($mouvement);
        $sousTotal = 0;

        foreach ($produits as $key => $produitId) {
            $mouvementItem = new MouvementItem();
            $produit = $this->entityManager->getReference(Produit::class, $produitId);
            $prixUt = $this->entityManager->getReference(Prix::class, $prixId[$key]);
            $stock = $this->entityManager->getRepository(Stock::class)->findByProduitUnite($produit, $prixUt->getUnite());
            if (!$stock) {
                $stock = new Stock();
                $stock->setProduit($produit);
                $stock->setUnite($prixUt->getUnite());
            }
            $stock->updateStock($quantite[$key], $isVente);

            $mouvementItem->setData($mouvement, $produit, $quantite[$key], $total[$key], $prixUt, $prixUt->getUnite());
            $this->entityManager->persist($mouvementItem);
            $sousTotal += $total[$key];
            $this->entityManager->persist($stock);
        }

        if ($sousTotal != $grandTotal) {
            //raise error
        }
        $this->entityManager->flush();
    }
}