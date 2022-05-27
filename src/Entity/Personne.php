<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Personne
 *
 * @ORM\Table(name="personne")
 * @ORM\Entity
 */
class Personne
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var \Date|null
     *
     * @ORM\Column(name="date_naissance", type="date", length=255, nullable=true)
     */
    private $date_naissance;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=12, nullable=true)
     */
    private $cin;

    /**
     * @var \Date|null
     *
     * @ORM\Column(name="date_cin", type="date", nullable=true)
     */
    private $date_cin;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_cin", type="string", length=100, nullable=true)
     */
    private $ville_cin;

    /**
     * @var \Date|null
     *
     * @ORM\Column(name="date_duplicata_cin", type="date", nullable=true)
     */
    private $date_duplicata_cin;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_duplicata_cin", type="string", length=100, nullable=true)
     */
    private $ville_duplicata_cin;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_1", type="string", length=13, nullable=false)
     */
    private $tel_1;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_2", type="string", length=13, nullable=true)
     */
    private $tel_2;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=180, nullable=true)
     */
    private $adresse;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AuthUser", mappedBy="personne", cascade={"persist"})
     */
    private $utilisateur;


    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getNom() . " " . $this->getPrenom();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getVilleCin(): ?string
    {
        return $this->ville_cin;
    }

    public function setVilleCin(string $ville_cin): self
    {
        $this->ville_cin = $ville_cin;

        return $this;
    }

    public function getDateDuplicataCin(): ?\DateTimeInterface
    {
        return $this->date_duplicata_cin;
    }

    public function setDateDuplicataCin(?\DateTimeInterface $date_duplicata_cin): self
    {
        $this->date_duplicata_cin = $date_duplicata_cin;

        return $this;
    }

    public function getVilleDuplicataCin(): ?string
    {
        return $this->ville_duplicata_cin;
    }

    public function setVilleDuplicataCin(?string $ville_duplicata_cin): self
    {
        $this->ville_duplicata_cin = $ville_duplicata_cin;

        return $this;
    }

    public function getTel1(): ?string
    {
        return $this->tel_1;
    }

    public function setTel1(string $tel_1): self
    {
        $this->tel_1 = $tel_1;

        return $this;
    }

    public function getTel2(): ?string
    {
        return $this->tel_2;
    }

    public function setTel2(?string $tel_2): self
    {
        $this->tel_2 = $tel_2;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }



    public function getDateCin(): ?\DateTimeInterface
    {
        return $this->date_cin;
    }

    public function setDateCin(?\DateTimeInterface $date_cin): self
    {
        $this->date_cin = $date_cin;

        return $this;
    }

    public function getUtilisateur(): ?AuthUser
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?AuthUser $utilisateur): self
    {
        // unset the owning side of the relation if necessary
        if ($utilisateur === null && $this->utilisateur !== null) {
            $this->utilisateur->setPersonne(null);
        }

        // set the owning side of the relation if necessary
        if ($utilisateur !== null && $utilisateur->getPersonne() !== $this) {
            $utilisateur->setPersonne($this);
        }

        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function createUser($email, $passwordEncoder)
    {
        if (!$this->getUtilisateur()) {
            $user = new AuthUser();
            $user->setPersonne($this);
            $user->setEmail($email);
            $user->setUsername($email);
            $user->setIsActive(true);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    AllConstants::DEFAULT_PASSWORD
                )
            );

            $this->setUtilisateur($user);
        }
        return $this->getUtilisateur();
    }
}
