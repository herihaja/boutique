<?php

namespace App\Entity;

use App\Repository\MouvementItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MouvementItemRepository::class)
 */
class MouvementItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="mouvements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=Mouvement::class, inversedBy="mouvementItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mouvement;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Prix::class, inversedBy="mouvementItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prixUT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setData($mouvement, $produit, $quantite, $total, $prixUt){
        $this->setMouvement($mouvement);
        $this->setProduit($produit);
        $this->setNombre($quantite);
        $this->setTotal($total);
        $this->setPrixUT($prixUt);
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

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPrixUT(): ?Prix
    {
        return $this->prixUT;
    }

    public function setPrixUT(?Prix $prixUT): self
    {
        $this->prixUT = $prixUT;

        return $this;
    }

    public function getMouvement(): ?Mouvement
    {
        return $this->mouvement;
    }

    public function setMouvement(?Mouvement $mouvement): self
    {
        $this->mouvement = $mouvement;

        return $this;
    }
}
