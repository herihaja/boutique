<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="quantite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=ParametreValeur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function updateStock($quantite, $isSortie){
        $qte = $this->getQuantite();
        $qte += ($isSortie) ? - $quantite : $quantite;

        /***  Handle smartly stock, use bigger entity stock if current one is exhausted */
        if ($qte < 0) {
            $biggerUniteAndMultiple = $this->getBiggerUniteStock();
            $biggerUnitestock = $biggerUniteAndMultiple[0];
            $biggerUnitestock->updateStock(1, $isSortie);
            $qte = $biggerUniteAndMultiple[1] + $qte;
        }

        $this->setQuantite($qte);
        return $this;        
    }

    public function getUnite(): ?ParametreValeur
    {
        return $this->unite;
    }

    public function setUnite(?ParametreValeur $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    public function getBiggerUniteStock(){
        $stocks = $this->getProduit()->getStocks();
        $relations = $this->getProduit()->getUniteRelations();
        $biggerUnite = [];

        foreach ($relations as $relation){
            if ($relation->getUnite1() == $this->getUnite() && $relation->getMultiple() < 1) {
                $biggerUnite[] = [$relation->getUnite2(), $relation->getMultiple()];
            }

            elseif($relation->getUnite2() == $this->getUnite() && $relation->getMultiple() > 1) {
                $biggerUnite[] = [$relation->getUnite1(), $relation->getMultiple()];
            }
        }

        if (count($biggerUnite) == 1) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("unite", $biggerUnite[0][0]))
                ->andWhere(Criteria::expr()->gt("quantite", 0));   

            return [$stocks->matching($criteria)->first(), $biggerUnite[0][1]];
        }
        
    }
}
