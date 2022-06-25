<?php

namespace App\Entity;

use App\Repository\UniteRelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UniteRelationRepository::class)
 * @ORM\Table( 
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="relation_unite_unique", columns={"produit_id", "unite1_id", "unite2_id"})
 *    }
 * )
 * 
 * @UniqueEntity(
 *     fields={"produit", "unite1", "unite2"}, 
 *     errorPath="unite1",
 *     message="Cette combinaison (produit, unités) existe déjà."
 * )
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
     * 
     *  @Assert\Expression(
     *     "this.getUnite1() != this.getUnite2()",
     *     message="Les unités ne doivent pas être la même"
     * )
     * @Assert\Expression(
     *     "this.getMultiple() != 1",
     *     message="Le multiple doit être différent de 1!"
     * )
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
