<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomFormation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionFormation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lien;

    /**
     * @ORM\ManyToOne(targetEntity=Domaine::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine;

    /**
     * @ORM\Column(type="float")
     */
    private $prixFormation;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFormation;

    /**
     * @ORM\OneToMany(targetEntity=Seance::class, mappedBy="formation", orphanRemoval=true)
     */
    private $seances;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formateur;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPlaces;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    public function setNomFormation(string $nomFormation): self
    {
        $this->nomFormation = $nomFormation;

        return $this;
    }

    public function getDescriptionFormation(): ?string
    {
        return $this->descriptionFormation;
    }

    public function setDescriptionFormation(string $descriptionFormation): self
    {
        $this->descriptionFormation = $descriptionFormation;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getPrixFormation(): ?float
    {
        return $this->prixFormation;
    }

    public function setPrixFormation(float $prixFormation): self
    {
        $this->prixFormation = $prixFormation;

        return $this;
    }

    public function getImageFormation(): ?string
    {
        return $this->imageFormation;
    }

    public function setImageFormation(string $imageFormation): self
    {
        $this->imageFormation = $imageFormation;

        return $this;
    }

    /**
     * @return Collection|Seance[]
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setFormation($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getFormation() === $this) {
                $seance->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormateur()
    {
        return $this->formateur;
    }

    /**
     * @param mixed $formateur
     */
    public function setFormateur($formateur): void
    {
        $this->formateur = $formateur;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }
}
