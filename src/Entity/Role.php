<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Titre déjà pris"
 * )
 *
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez remplir ce champ")
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", inversedBy="userRoles")
     *
     */
    private $Users;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Vous devez remplir ce champ")
     */
    private $rang;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuAccess", inversedBy="roles")
     */
    private $menuAccess;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }



    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users[] = $user;
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->Users->contains($user)) {
            $this->Users->removeElement($user);
        }

        return $this;
    }

    public function getRang(): ?int
    {
        return $this->rang;
    }

    public function setRang(int $rang): self
    {
        $this->rang = $rang;

        return $this;
    }

    public function getMenuAccess(): ?MenuAccess
    {
        return $this->menuAccess;
    }

    public function setMenuAccess(?MenuAccess $menuAccess): self
    {
        $this->menuAccess = $menuAccess;

        return $this;
    }
}
