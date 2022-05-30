<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
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
}
