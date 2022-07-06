<?php

namespace App\Tests;

use App\Entity\Stock;
use App\Entity\Produit;
use App\Entity\Prix;
use App\Entity\ParametreValeur;
use App\Entity\UniteRelation;
use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;

class DecrementStockTest extends TestCase
{
    public function testUpdateStock(): void
    {
        $prix = new Prix();
        $produit = new Produit();
        $produit->setNom("Mofo");
        $produit->setDescription(1100);

        $unite = new ParametreValeur();
        $unite->setValeur("Kg");

        $uniteSac = new ParametreValeur();
        $uniteSac->setValeur("SAC");

        $prix = new Prix();
        $prix->setProduit($produit);
        $prix->setUnite($unite);        

        $produitMock = $this->createMock(Produit::class);

        $stockKg = new Stock();
        $stockKg->setProduit($produit);
        $stockKg->setUnite($unite);
        $stockKg->setQuantite(10);

        $stockSac = new Stock();
        $stockSac->setProduit($produit);
        $stockSac->setUnite($uniteSac);
        $stockSac->setQuantite(10);

        $relation = new UniteRelation();
        $relation->setProduit($produit);
        $relation->setUnite1($uniteSac);
        $relation->setMultiple(50);
        $relation->setUnite2($unite);

        $stockMock = new ArrayCollection([$stockKg, $stockSac]);

        $produitMock->expects($this->any())
            ->method('getStocks')
            ->willReturn($stockMock);

        $produitMock->expects($this->any())
            ->method('getUniteRelations')
            ->willReturn(new ArrayCollection([$relation]));
        $stockKg->setProduit($produitMock);

        $stockKg->updateStock(14, true);

        $this->assertEquals($stockKg->getQuantite(), 46);
        $this->assertEquals($stockSac->getQuantite(), 9);
    }
}
