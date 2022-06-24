<?php

namespace App\Entity;

use App\Repository\MouvementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MouvementRepository::class)
 */
class Mouvement
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
    private $dateMouvement;

    /**
     * @ORM\ManyToOne(targetEntity=AuthUser::class, inversedBy="mouvementTraites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $caissier;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montantTotal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVente;

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
     * @ORM\OneToMany(targetEntity=MouvementItem::class, mappedBy="mouvement")
     */
    private $mouvementItems;



    public function __construct()
    {
        $this->mouvementItems = new ArrayCollection();
        $this->setDateMouvement(new \Datetime());
    }

    public function setVenteData($grandTotal, $montantRemis, $montantRendu, $user){
        $this->setMontantTotal($grandTotal);
        $this->setMontantRemis($montantRemis);
        $this->setMontantRendu($montantRendu);
        $this->setCaissier($user);
        $this->setIsVente(true);
    }

    public function setApproData($user){
        $this->setMontantTotal(0);
        $this->setMontantRemis(0);
        $this->setMontantRendu(0);
        $this->setCaissier($user);
        $this->setIsVente(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateMouvement(): ?\DateTimeInterface
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(\DateTimeInterface $dateMouvement): self
    {
        $this->dateMouvement = $dateMouvement;

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

    public function isIsVente(): ?bool
    {
        return $this->isVente;
    }

    public function setIsVente(bool $isVente): self
    {
        $this->isVente = $isVente;

        return $this;
    }

    /**
     * @return Collection<int, MouvementItem>
     */
    public function getMouvementItems(): Collection
    {
        return $this->mouvementItems;
    }

    public function addMouvementItem(MouvementItem $mouvementItem): self
    {
        if (!$this->mouvementItems->contains($mouvementItem)) {
            $this->mouvementItems[] = $mouvementItem;
            $mouvementItem->setMouvement($this);
        }

        return $this;
    }

    public function removeMouvementItem(MouvementItem $mouvementItem): self
    {
        if ($this->mouvementItems->removeElement($mouvementItem)) {
            // set the owning side to null (unless already changed)
            if ($mouvementItem->getMouvement() === $this) {
                $mouvementItem->setMouvement(null);
            }
        }

        return $this;
    }

    
}
