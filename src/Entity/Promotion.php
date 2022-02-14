<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
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
    private $P_Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $P_Value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $P_Domaine;

    /**
     * @ORM\Column(type="date")
     */
    private $P_DateB;

    /**
     * @ORM\Column(type="date")

     */
    private $P_DateF;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $P_Note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPName(): ?string
    {
        return $this->P_Name;
    }

    public function setPName(string $P_Name): self
    {
        $this->P_Name = $P_Name;

        return $this;
    }

    public function getPValue(): ?int
    {
        return $this->P_Value;
    }

    public function setPValue(int $P_Value): self
    {
        $this->P_Value = $P_Value;

        return $this;
    }

    public function getPDomaine(): ?string
    {
        return $this->P_Domaine;
    }

    public function setPDomaine(string $P_Domaine): self
    {
        $this->P_Domaine = $P_Domaine;

        return $this;
    }

    public function getPDateB(): ?\DateTimeInterface
    {
        return $this->P_DateB;
    }

    public function setPDateB(\DateTimeInterface $P_DateB): self
    {
        $this->P_DateB = $P_DateB;

        return $this;
    }

    public function getPDateF(): ?\DateTimeInterface
    {
        return $this->P_DateF;
    }

    public function setPDateF(\DateTimeInterface $P_DateF): self
    {
        $this->P_DateF = $P_DateF;

        return $this;
    }

    public function getPNote(): ?string
    {
        return $this->P_Note;
    }

    public function setPNote(?string $P_Note): self
    {
        $this->P_Note = $P_Note;

        return $this;
    }
}
