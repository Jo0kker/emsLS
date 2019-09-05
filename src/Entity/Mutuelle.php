<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MutuelleRepository")
 */
class Mutuelle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slogan;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users", mappedBy="mutuelle")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clients", mappedBy="mutuelle")
     */
    private $clients;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
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

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbInter(): ?int
    {
        return $this->nbInter;
    }

    public function setNbInter(int $nbInter): self
    {
        $this->nbInter = $nbInter;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setMutuelle($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getMutuelle() === $this) {
                $user->setMutuelle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Clients[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClients(Clients $clients): self
    {
        if (!$this->clients->contains($clients)) {
            $this->clients[] = $clients;
            $clients->setMutuelle($this);
        }

        return $this;
    }

    public function removeClients(Clients $clients): self
    {
        if ($this->clients->contains($clients)) {
            $this->clients->removeElement($clients);
            // set the owning side to null (unless already changed)
            if ($clients->getMutuelle() === $this) {
                $clients->setMutuelle(null);
            }
        }

        return $this;
    }
}
