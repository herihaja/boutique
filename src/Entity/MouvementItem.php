<?php

namespace App\Entity;

use App\Repository\MouvementItemRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

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
     * @ORM\JoinColumn(nullable=true)
     */
    private $prixUT;

    /**
     * @ORM\ManyToOne(targetEntity=ParametreValeur::class, inversedBy="mouvementItems")
     * @ORM\JoinColumn(nullable=true)
     */
    private $unite;

    /**
     * @var \Date
     *
     * @ORM\Column(name="date_peremption", type="date")
     */
    private $datePeremption;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setData($mouvement, $produit, $quantite, $total, $prixUt, $unite, $datePeremption){
        $this->setMouvement($mouvement);
        $this->setProduit($produit);
        $this->setNombre($quantite);
        $this->setTotal($total);
        $this->setPrixUT($prixUt);
        $this->setUnite($unite)
            ->setDatePeremption($datePeremption);
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

    public function getUnite(): ?ParametreValeur
    {
        return $this->unite;
    }

    public function setUnite(?ParametreValeur $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    public function getDatePeremption(): ?\DateTimeInterface
    {
        return $this->datePeremption;
    }

    public function setDatePeremption(\DateTimeInterface $datePeremption): self
    {
        $this->datePeremption = $datePeremption;

        return $this;
    }
}
