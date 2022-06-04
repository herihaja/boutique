<?php

namespace App\Entity;

use App\Repository\AchatItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AchatItemRepository::class)
 */
class AchatItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=Achat::class, inversedBy="achatItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $achat;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

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

    public function getAchat(): ?Achat
    {
        return $this->achat;
    }

    public function setAchat(?Achat $achat): self
    {
        $this->achat = $achat;

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
}
