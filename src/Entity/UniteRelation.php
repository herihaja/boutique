<?php

namespace App\Entity;

use App\Repository\UniteRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UniteRelationRepository::class)
 */
class UniteRelation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="uniteRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=ParametreValeur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite1;

    /**
     * @ORM\Column(type="float")
     */
    private $multiple;

    /**
     * @ORM\ManyToOne(targetEntity=ParametreValeur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unite2;

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

    public function getUnite1(): ?ParametreValeur
    {
        return $this->unite1;
    }

    public function setUnite1(?ParametreValeur $unite1): self
    {
        $this->unite1 = $unite1;

        return $this;
    }

    public function getMultiple(): ?float
    {
        return $this->multiple;
    }

    public function setMultiple(float $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getUnite2(): ?ParametreValeur
    {
        return $this->unite2;
    }

    public function setUnite2(?ParametreValeur $unite2): self
    {
        $this->unite2 = $unite2;

        return $this;
    }

    public function __toString()
    {
        return $this->getProduit()." ".$this->getUnite1()." ".$this->getUnite2();
    }
}
