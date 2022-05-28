<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ParametreValeur
 *
 * @ORM\Table(name="parametre_valeur")
 * @ORM\Entity(repositoryClass="App\Repository\ParametreValeurRepository")
 */
class ParametreValeur
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
     * @ORM\Column(name="code_valeur", type="string", length=32, nullable=false)
     */
    private $codeValeur;

    /**
     * @var text
     *
     * @ORM\Column(name="valeur", type="text", nullable=false)
     */
    private $valeur;

    /**
     * @var int
     *
     * @ORM\Column(name="valeur2", type="integer", nullable=true)
     */
    private $valeur2;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Parametre", inversedBy="parametre_valeurs")
     * @ORM\JoinColumn(name="id_parametre", referencedColumnName="id", nullable=true)
     */
    private $parametre;


    public function __construct()
    {
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getValeur2(): ?int
    {
        return $this->valeur2;
    }

    public function setValeur2(int $valeur2): self
    {
        $this->valeur2 = $valeur2;

        return $this;
    }

    public function getParametre(): ?Parametre
    {
        return $this->parametre;
    }

    public function setParametre(?Parametre $parametre): self
    {
        $this->parametre = $parametre;

        return $this;
    }

    public function __toString()
    {
        return $this->valeur; // . "(" . $this->code_valeur . ")";
    }



    public static function getByCode($entityManager, $code)
    {
        return $entityManager->getRepository(ParametreValeur::class)->findOneBy(array('code_valeur' => $code));
    }

    public function getCodeValeur(): ?string
    {
        return $this->codeValeur;
    }

    public function setCodeValeur(string $codeValeur): self
    {
        $this->codeValeur = $codeValeur;

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
