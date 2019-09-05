<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Clients
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
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $obs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emploi;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ppa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mutuelle", inversedBy="clients")
     */
    private $mutuelle;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $nbInter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervention", mappedBy="client")
     */
    private $interventions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
    }

    /**
     * Gère le nombre d'inter a la création d'un dossier
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     * @throws \Exception
     */
    public function prePersist()
    {

        if (empty($this->nbInter)) {
            $this->nbInter = $this->getMutuelle()->getNbInter();
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

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getObs(): ?string
    {
        return $this->obs;
    }

    public function setObs(string $obs): self
    {
        $this->obs = $obs;

        return $this;
    }

    public function getEmploi(): ?string
    {
        return $this->emploi;
    }

    public function setEmploi(string $emploi): self
    {
        $this->emploi = $emploi;

        return $this;
    }

    public function getPpa(): ?bool
    {
        return $this->ppa;
    }

    public function setPpa(bool $ppa): self
    {
        $this->ppa = $ppa;

        return $this;
    }

    public function getMutuelle(): ?Mutuelle
    {
        return $this->mutuelle;
    }

    public function setMutuelle(?Mutuelle $mutuelle): self
    {
        $this->mutuelle = $mutuelle;

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
     * @return Collection|Intervention[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setClient($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            // set the owning side to null (unless already changed)
            if ($intervention->getClient() === $this) {
                $intervention->setClient(null);
            }
        }

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }
}
