<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Parametre
 *
 * @ORM\Table(name="parametre")
 * @ORM\Entity
 */
class Parametre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id_parametre;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_parametre", type="string", length=254, nullable=false)
     */
    private $nom_parametre;

    /**
     * @var text
     *
     * @ORM\Column(name="description_parametre", type="text", nullable=false)
     */
    private $description_parametre;

    public function getIdParametre(): ?int
    {
        return $this->id_parametre;
    }

    public function setIdParametre($id): self
    {
        $this->id_parametre = $id;
        return $this;
    }

    public function getNomParametre(): ?string
    {
        return $this->nom_parametre;
    }

    public function setNomParametre(string $nom_parametre): self
    {
        $this->nom_parametre = $nom_parametre;

        return $this;
    }

    public function getDescriptionParametre(): ?string
    {
        return $this->description_parametre;
    }

    public function setDescriptionParametre(string $description_parametre): self
    {
        $this->description_parametre = $description_parametre;

        return $this;
    }

    public function __toString()
    {
        return $this->nom_parametre;
    }
}
