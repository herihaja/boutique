<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AchatRepository::class)
 */
class Achat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAchat;

    /**
     * @ORM\ManyToOne(targetEntity=AuthUser::class, inversedBy="achatTraites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $caissier;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montantTotal;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montantRemis;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $montantRendu;

    /**
     * @ORM\ManyToOne(targetEntity=ParametreValeur::class)
     */
    private $modePaiement;

    /**
     * @ORM\OneToMany(targetEntity=AchatItem::class, mappedBy="achat")
     */
    private $achatItems;

    public function __construct()
    {
        $this->achatItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getCaissier(): ?AuthUser
    {
        return $this->caissier;
    }

    public function setCaissier(?AuthUser $caissier): self
    {
        $this->caissier = $caissier;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): self
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getMontantRemis(): ?string
    {
        return $this->montantRemis;
    }

    public function setMontantRemis(string $montantRemis): self
    {
        $this->montantRemis = $montantRemis;

        return $this;
    }

    public function getMontantRendu(): ?string
    {
        return $this->montantRendu;
    }

    public function setMontantRendu(?string $montantRendu): self
    {
        $this->montantRendu = $montantRendu;

        return $this;
    }

    public function getModePaiement(): ?ParametreValeur
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?ParametreValeur $modePaiement): self
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * @return Collection<int, AchatItem>
     */
    public function getAchatItems(): Collection
    {
        return $this->achatItems;
    }

    public function addAchatItem(AchatItem $achatItem): self
    {
        if (!$this->achatItems->contains($achatItem)) {
            $this->achatItems[] = $achatItem;
            $achatItem->setAchat($this);
        }

        return $this;
    }

    public function removeAchatItem(AchatItem $achatItem): self
    {
        if ($this->achatItems->removeElement($achatItem)) {
            // set the owning side to null (unless already changed)
            if ($achatItem->getAchat() === $this) {
                $achatItem->setAchat(null);
            }
        }

        return $this;
    }
}
