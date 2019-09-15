<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Intervention
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="interventions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Clients", inversedBy="interventions")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeInter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $constatation;

    /**
     * @ORM\Column(type="text")
     */
    private $soinAppli;

    /**
     * @ORM\Column(type="boolean")
     */
    private $soinCover;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuInter;

    /**
     * Gère la date de création
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     * @throws \Exception
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $now = new \DateTime;
            $now->setTimezone(new \DateTimeZone('Europe/Paris'));
            $this->createdAt = $now;
        }
    }


    public function getFullName()
    {
        return "{$this->nom} {$this->prenom}";
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getTypeInter(): ?string
    {
        return $this->typeInter;
    }

    public function setTypeInter(string $typeInter): self
    {
        $this->typeInter = $typeInter;

        return $this;
    }

    public function getConstatation(): ?string
    {
        return $this->constatation;
    }

    public function setConstatation(string $constatation): self
    {
        $this->constatation = $constatation;

        return $this;
    }

    public function getSoinAppli(): ?string
    {
        return $this->soinAppli;
    }

    public function setSoinAppli(string $soinAppli): self
    {
        $this->soinAppli = $soinAppli;

        return $this;
    }

    public function getSoinCover(): ?bool
    {
        return $this->soinCover;
    }

    public function setSoinCover(bool $soinCover): self
    {
        $this->soinCover = $soinCover;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLieuInter(): ?string
    {
        return $this->lieuInter;
    }

    public function setLieuInter(string $lieuInter): self
    {
        $this->lieuInter = $lieuInter;

        return $this;
    }
}
