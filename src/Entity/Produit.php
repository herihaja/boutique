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
     * @ORM\OneToMany(targetEntity=AchatItem::class, mappedBy="produit")
     */
    private $achats;

    public function __construct()
    {
        $this->prix = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->unites = new ArrayCollection();
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
     * @return Collection<int, AchatItem>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(AchatItem $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setProduit($this);
        }

        return $this;
    }

    public function removeAchat(AchatItem $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getProduit() === $this) {
                $achat->setProduit(null);
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
}
