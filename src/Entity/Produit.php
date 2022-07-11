<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $barCode;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $qrCode;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ParametreValeur", inversedBy="produitsByCat")
     * @ORM\JoinColumn(name="id_parametre", referencedColumnName="id", nullable=true)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ParametreValeur", inversedBy="produitsByType")
     * @ORM\JoinColumn(name="id_type", referencedColumnName="id", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ParametreValeur", inversedBy="produitsByUnite")
     * @ORM\JoinTable(name="produit_unites")
     */
    private $unites;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var blob
     *
     * @ORM\Column(name="image", type="blob",  nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Prix::class, mappedBy="produit", orphanRemoval=true)
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=MouvementItem::class, mappedBy="produit")
     */
    private $mouvements;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="produit")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=UniteRelation::class, mappedBy="produit", orphanRemoval=true)
     */
    private $uniteRelations;

    public function __construct()
    {
        $this->prix = new ArrayCollection();
        $this->mouvements = new ArrayCollection();
        $this->unites = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->uniteRelations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?ParametreValeur
    {
        return $this->categorie;
    }

    public function setCategorie(?ParametreValeur $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getType(): ?ParametreValeur
    {
        return $this->type;
    }

    public function setType(?ParametreValeur $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        if ($image) {
            $this->image = file_get_contents($image);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getEncodedImage()
    {
        return base64_encode(stream_get_contents($this->image));
    }

    /**
     * @return Collection<int, Prix>
     */
    public function getPrix(): Collection
    {
        return $this->prix;
    }

    public function addPrix(Prix $prix): self
    {
        if (!$this->prix->contains($prix)) {
            $this->prix[] = $prix;
            $prix->setProduit($this);
        }

        return $this;
    }

    public function removePrix(Prix $prix): self
    {
        if ($this->prix->removeElement($prix)) {
            // set the owning side to null (unless already changed)
            if ($prix->getProduit() === $this) {
                $prix->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementItem>
     */
    public function getMouvements(): Collection
    {
        return $this->mouvements;
    }

    public function addMouvement(MouvementItem $mouvement): self
    {
        if (!$this->mouvements->contains($mouvement)) {
            $this->mouvements[] = $mouvement;
            $mouvement->setProduit($this);
        }

        return $this;
    }

    public function removeMouvement(MouvementItem $mouvement): self
    {
        if ($this->mouvements->removeElement($mouvement)) {
            // set the owning side to null (unless already changed)
            if ($mouvement->getProduit() === $this) {
                $mouvement->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParametreValeur>
     */
    public function getUnites(): Collection
    {
        return $this->unites;
    }

    public function addUnite(ParametreValeur $unite): self
    {
        if (!$this->unites->contains($unite)) {
            $this->unites[] = $unite;
        }

        return $this;
    }

    public function removeUnite(ParametreValeur $unite): self
    {
        $this->unites->removeElement($unite);

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduit($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduit() === $this) {
                $stock->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UniteRelation>
     */
    public function getUniteRelations(): Collection
    {
        return $this->uniteRelations;
    }

    public function addUniteRelation(UniteRelation $uniteRelation): self
    {
        if (!$this->uniteRelations->contains($uniteRelation)) {
            $this->uniteRelations[] = $uniteRelation;
            $uniteRelation->setProduit($this);
        }

        return $this;
    }

    public function removeUniteRelation(UniteRelation $uniteRelation): self
    {
        if ($this->uniteRelations->removeElement($uniteRelation)) {
            // set the owning side to null (unless already changed)
            if ($uniteRelation->getProduit() === $this) {
                $uniteRelation->setProduit(null);
            }
        }

        return $this;
    }

    public function getBarCode(): ?string
    {
        return $this->barCode;
    }

    public function setBarCode(?string $barCode): self
    {
        $this->barCode = $barCode;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(?string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }
}
