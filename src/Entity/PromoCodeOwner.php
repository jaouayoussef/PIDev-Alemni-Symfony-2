<?php

namespace App\Entity;

use App\Repository\PromoCodeOwnerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromoCodeOwnerRepository::class)
 */
class PromoCodeOwner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PromotionCode", mappedBy="CP_PCD")
     */
    private $PCD_PromotionCode;



    /**
     * @return mixed
     */
    public function getPCDPromotionCode()
    {
        return $this->PCD_PromotionCode;
    }

    /**
     * @param mixed $PCD_PromotionCode
     */
    public function setPCDPromotionCode($PCD_PromotionCode): void
    {
        $this->PCD_PromotionCode = $PCD_PromotionCode;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PCD_Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PCD_FirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PCD_Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PCD_Job;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $PCD_TelephoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PCD_Gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PCD_City;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PCD_Note;

    function __toString()
    {
        return $this->PCD_Name;
    }

    public function __construct()
    {
        $this->PCD_PromotionCode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPCDName(): ?string
    {
        return $this->PCD_Name;
    }

    public function setPCDName(string $PCD_Name): self
    {
        $this->PCD_Name = $PCD_Name;

        return $this;
    }

    public function getPCDFirstName(): ?string
    {
        return $this->PCD_FirstName;
    }

    public function setPCDFirstName(string $PCD_FirstName): self
    {
        $this->PCD_FirstName = $PCD_FirstName;

        return $this;
    }

    public function getPCDEmail(): ?string
    {
        return $this->PCD_Email;
    }

    public function setPCDEmail(string $PCD_Email): self
    {
        $this->PCD_Email = $PCD_Email;

        return $this;
    }

    public function getPCDJob(): ?string
    {
        return $this->PCD_Job;
    }

    public function setPCDJob(string $PCD_Job): self
    {
        $this->PCD_Job = $PCD_Job;

        return $this;
    }

    public function getPCDTelephoneNumber(): ?string
    {
        return $this->PCD_TelephoneNumber;
    }

    public function setPCDTelephoneNumber(string $PCD_TelephoneNumber): self
    {
        $this->PCD_TelephoneNumber = $PCD_TelephoneNumber;

        return $this;
    }

    public function getPCDGender(): ?string
    {
        return $this->PCD_Gender;
    }

    public function setPCDGender(string $PCD_Gender): self
    {
        $this->PCD_Gender = $PCD_Gender;

        return $this;
    }

    public function getPCDCity(): ?string
    {
        return $this->PCD_City;
    }

    public function setPCDCity(string $PCD_City): self
    {
        $this->PCD_City = $PCD_City;

        return $this;
    }

    public function getPCDNote(): ?string
    {
        return $this->PCD_Note;
    }

    public function setPCDNote(?string $PCD_Note): self
    {
        $this->PCD_Note = $PCD_Note;

        return $this;
    }
}
