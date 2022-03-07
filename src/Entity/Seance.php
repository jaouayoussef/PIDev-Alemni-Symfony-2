<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeanceRepository::class)
 */
class Seance
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
    private $nomSeance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionSeance;

    /**
     * @ORM\Column(type="date")
     */
    private $dateSeance;

    /**
     * @ORM\Column(type="time")
     */
    private $heureDeb;

    /**
     * @ORM\Column(type="time")
     */
    private $heureFin;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="seances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSeance(): ?string
    {
        return $this->nomSeance;
    }

    public function setNomSeance(string $nomSeance): self
    {
        $this->nomSeance = $nomSeance;

        return $this;
    }

    public function getDescriptionSeance(): ?string
    {
        return $this->descriptionSeance;
    }

    public function setDescriptionSeance(string $descriptionSeance): self
    {
        $this->descriptionSeance = $descriptionSeance;

        return $this;
    }

    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->dateSeance;
    }

    public function setDateSeance(\DateTimeInterface $dateSeance): self
    {
        $this->dateSeance = $dateSeance;

        return $this;
    }

    public function getHeureDeb(): ?\DateTimeInterface
    {
        return $this->heureDeb;
    }

    public function setHeureDeb(\DateTimeInterface $heureDeb): self
    {
        $this->heureDeb = $heureDeb;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }
}
