<?php

namespace App\Entity;

use App\Repository\DomaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomaineRepository::class)
 */
class Domaine
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
    private $nomDomaine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionDomaine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageDomaine;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="domaine", orphanRemoval=true)
     */
    private $formations;

    /**
     * @ORM\OneToMany (targetEntity=Promotion::class, mappedBy="P_Domaine", orphanRemoval=true)
     */
    private $promotions;



    public function __construct()
    {
        $this->formations = new ArrayCollection();
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDomaine(): ?string
    {
        return $this->nomDomaine;
    }

    public function setNomDomaine(string $nomDomaine): self
    {
        $this->nomDomaine = $nomDomaine;

        return $this;
    }

    public function getDescriptionDomaine(): ?string
    {
        return $this->descriptionDomaine;
    }

    public function setDescriptionDomaine(string $descriptionDomaine): self
    {
        $this->descriptionDomaine = $descriptionDomaine;

        return $this;
    }

    public function getImageDomaine(): ?string
    {
        return $this->imageDomaine;
    }

    public function setImageDomaine(string $imageDomaine): self
    {
        $this->imageDomaine = $imageDomaine;

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setDomaine($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getDomaine() === $this) {
                $formation->setDomaine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setPDomaine($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            // set the owning side to null (unless already changed)
            if ($promotion->getPDomaine() === $this) {
                $promotion->setPDomaine(null);
            }
        }

        return $this;
    }



}
