<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="user_username_key", columns={"username"})}, indexes={@ORM\Index(name="user_username_6821ab7c_like", columns={"username"})})
 * @ORM\Entity(repositoryClass="App\Repository\AuthUserRepository")
 */
class AuthUser implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(name="password", type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetimetz", nullable=true)
     */
    private $lastLogin;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_superuser", type="boolean", nullable=true)
     */
    private $isSuperuser;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=150, nullable=false)
     */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=true)
     */
    private $email;


    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"default" : true})
     */
    private $isActive;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_joined", type="datetimetz", nullable=true)
     */
    private $dateJoined;



    /**
     * @var blob
     *
     * @ORM\Column(name="avatar", type="blob",  nullable=true)
     */
    private $avatar;



    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AuthGroup", inversedBy="users", cascade={"persist"})
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AuthPermission", inversedBy="users")
     */
    private $permissions;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Personne", inversedBy="utilisateur", cascade={"persist"})
     */
    private $personne;

    /**
     * @ORM\OneToMany(targetEntity=Mouvement::class, mappedBy="caissier")
     */
    private $mouvementTraites;



    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->mouvementTraites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getIsSuperuser(): ?bool
    {
        return $this->isSuperuser;
    }

    public function setIsSuperuser(bool $isSuperuser): self
    {
        $this->isSuperuser = $isSuperuser;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getDateJoined(): ?\DateTimeInterface
    {
        return $this->dateJoined;
    }

    public function setDateJoined($dateJoined): self
    {
        $this->dateJoined = $dateJoined;
        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getEncodedAvatar()
    {
        return base64_encode(stream_get_contents($this->avatar));
    }

    public function setAvatar($avatar): self
    {
        if ($avatar) {
            $this->avatar = file_get_contents($avatar);
        }

        return $this;
    }



    /**
     * @return Collection|AuthGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(AuthGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(AuthGroup $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * @return Collection|AuthPermission[]
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(AuthPermission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    public function removePermission(AuthPermission $permission): self
    {
        $this->permissions->removeElement($permission);

        return $this;
    }

    public function __toString()
    {
        return $this->personne . "";
    }



    public function getSalt()
    {
        // not needed when using the "auto" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $roles = [];
        foreach ($this->permissions as $role)
            $roles[] = $role->getCodename();
        //return $roles;
        foreach ($this->groups as $groupe) {
            foreach ($groupe->getPermissions() as $role)
                $roles[] = $role->getCodename();
        }

        return $roles;
    }

    public static function getById($entityManager, $id)
    {
        return $entityManager->getRepository(AuthUser::class)
            ->findOneById($id);
    }

    public function getFullName()
    {
        return $this->personne->getPrenom() . " " . $this->personne->getNom();
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function isIsSuperuser(): ?bool
    {
        return $this->isSuperuser;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @return Collection<int, Mouvement>
     */
    public function getMouvementTraites(): Collection
    {
        return $this->mouvementTraites;
    }

    public function addMouvementTraite(Mouvement $mouvementTraite): self
    {
        if (!$this->mouvementTraites->contains($mouvementTraite)) {
            $this->mouvementTraites[] = $mouvementTraite;
            $mouvementTraite->setCaissier($this);
        }

        return $this;
    }

    public function removeMouvementTraite(Mouvement $mouvementTraite): self
    {
        if ($this->mouvementTraites->removeElement($mouvementTraite)) {
            // set the owning side to null (unless already changed)
            if ($mouvementTraite->getCaissier() === $this) {
                $mouvementTraite->setCaissier(null);
            }
        }

        return $this;
    }

    public function setSingleGroup(AuthGroup $group){
        $this->groups->clear();
        $this->addGroup($group);
    }

    public function getSingleGroup(){
        return $this->groups->first();
    }
}
