<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

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

    public function updateStock($quantite, $sortie){
        $qte = $this->getQuantite();
        $qte += ($sortie) ? - $quantite : $quantite;
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

}
