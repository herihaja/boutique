<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AuthPermission
 *
 * @ORM\Table(name="auth_permission")
 * @ORM\Entity(repositoryClass="App\Repository\AuthPermissionRepository")
 */
class AuthPermission
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="codename", type="string", length=100, nullable=false)
     */
    private $codename;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AuthUser", mappedBy="permissions")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AuthGroup", mappedBy="permissions")
     */
    private $groups;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCodename(): ?string
    {
        return $this->codename;
    }

    public function setCodename(string $codename): self
    {
        $this->codename = $codename;

        return $this;
    }

    /**
     * @return Collection|AuthUser[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(AuthUser $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(AuthUser $user): self
    {
        $this->users->removeElement($user);

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

    public function __toString()
    {
        return $this->name;
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
