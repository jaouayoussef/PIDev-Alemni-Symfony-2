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
     * @ORM\Column(type="integer")
     */
    private $nbPlaces;


    /**
     * @ORM\Column(type="integer")
     */
    private $placesReserve;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formateur;

    /**
     * @ORM\OneToMany(targetEntity=ReservationFormation::class, mappedBy="formation", orphanRemoval=true)
     */
    private $reservationFormations;

    /**
     * @ORM\OneToOne(targetEntity=Quiz::class, mappedBy="id_formation", cascade={"persist", "remove"})
     */
    private $quiz;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
        $this->seances = new ArrayCollection();
        $this->reservationFormations = new ArrayCollection();

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


    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }


    public function getPlacesReserve(): ?int
    {
        return $this->placesReserve;
    }

    public function setPlacesReserve(int $placesReserve): self
    {
        $this->placesReserve = $placesReserve;

        return $this;
    }

    public function getFormateur(): ?User
    {
        return $this->formateur;
    }

    public function setFormateur(?User $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|ReservationFormation[]
     */
    public function getReservationFormations(): Collection
    {
        return $this->reservationFormations;
    }

    public function addReservationFormation(ReservationFormation $reservationFormation): self
    {
        if (!$this->reservationFormations->contains($reservationFormation)) {
            $this->reservationFormations[] = $reservationFormation;
            $reservationFormation->setFormation($this);
        }

        return $this;
    }

    public function removeReservationFormation(ReservationFormation $reservationFormation): self
    {
        if ($this->reservationFormations->removeElement($reservationFormation)) {
            // set the owning side to null (unless already changed)
            if ($reservationFormation->getFormation() === $this) {
                $reservationFormation->setFormation(null);
            }
        }

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        // unset the owning side of the relation if necessary
        if ($quiz === null && $this->quiz !== null) {
            $this->quiz->setIdFormation(null);
        }

        // set the owning side of the relation if necessary
        if ($quiz !== null && $quiz->getIdFormation() !== $this) {
            $quiz->setIdFormation($this);
        }

        $this->quiz = $quiz;

        return $this;
    }
}
