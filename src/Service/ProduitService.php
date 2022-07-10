<?php 

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use App\Entity\Mouvement;
use App\Entity\MouvementItem;
use App\Entity\Stock;
use App\Entity\Produit;
use App\Entity\Prix;
use App\Entity\ParametreValeur;


class ProduitService
{
    private EntityManager $entityManager;


    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        $this->entityManager = $doctrine->getManager();
    } 


    public function handlePostDataCaisse($postData, $user, $isVente=true)
    {
        $produits = $postData->all('produit');
        $quantite = $postData->all('quantite');
        $prixId = $postData->all('prixId');
        $total = $postData->all('total');
        $grandTotal = $postData->get('grandTotal');
        $montantRemis = $postData->get('montantRemis');
        $montantRendu = $postData->get('montantRendu');

        $mouvement = new Mouvement();
        if ($isVente)
            $mouvement->setVenteData($grandTotal, $montantRemis, $montantRendu, $user);
        else{
            $mouvement->setApproData($user);
            $unites = $postData->all('unite');
        }
        $this->entityManager->persist($mouvement);
        $sousTotal = 0;

        foreach ($produits as $key => $produitId) {
            $mouvementItem = new MouvementItem();
            $produit = $this->entityManager->getReference(Produit::class, $produitId);
            
            if ($isVente) {
                $prixUt = $this->entityManager->getReference(Prix::class, $prixId[$key]);
                $unite = $prixUt->getUnite();
            } else {
                $unite = $this->entityManager->getReference(ParametreValeur::class, $unites[$key]);
                $prixUt = null;
            }

            $stock = $this->entityManager->getRepository(Stock::class)->findByProduitUnite($produit, $unite);
            if (!$stock) {
                $stock = new Stock();
                $stock->setProduit($produit);
                $stock->setUnite($unite);
            }
            $stock->updateStock($quantite[$key], $isVente);

            $mouvementItem->setData($mouvement, $produit, $quantite[$key], $total[$key], $prixUt, $unite);
            $this->entityManager->persist($mouvementItem);
            $sousTotal += $total[$key];
            $this->entityManager->persist($stock);
        }

        if ($sousTotal != $grandTotal) {
            //raise error
        }
        $this->entityManager->flush();
    }

    public function handleApprovisionnement($postData, $user){
        $this->handlePostDataCaisse($postData, $user, false);
    }

    public function getFrequenceVente(){
        return ['mofo'=>120, 'vitogaz'=> 50];
    }
}