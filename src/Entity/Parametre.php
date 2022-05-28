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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_parametre", type="string", length=254, nullable=false)
     */
    private $nomParametre;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    public function __toString()
    {
        return $this->nomParametre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomParametre(): ?string
    {
        return $this->nomParametre;
    }

    public function setNomParametre(string $nomParametre): self
    {
        $this->nomParametre = $nomParametre;

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
}
