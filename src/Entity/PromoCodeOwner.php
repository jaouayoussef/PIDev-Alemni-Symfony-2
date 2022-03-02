<?php

namespace App\Entity;

use App\Repository\PromoCodeOwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=PromoCodeOwnerRepository::class)
 */
class PromoCodeOwner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"romoodewners"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})

     */
    private $PCD_Name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})
     */
    private $PCD_FirstName;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})
     */
    private $PCD_Email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})
     */
    private $PCD_Job;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})
     */
    private $PCD_TelephoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})
     */
    private $PCD_Gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     * @Groups ({"romoodewners"})
     */
    private $PCD_City;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"romoodewners"})
     */
    private $PCD_Note;

    /**
     * @ORM\OneToMany(targetEntity=PromotionCode::class, mappedBy="CP_PCD", orphanRemoval=true)
     * @Groups ({"romoodewners"})
     */
    private $PCD_PromotionCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $PCD_NbrePromo = 0;

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

    /**
     * @return Collection|PromotionCode[]
     */
    public function getPCDPromotionCode(): Collection
    {
        return $this->PCD_PromotionCode;
    }

    public function addPCDPromotionCode(PromotionCode $pCDPromotionCode): self
    {
        if (!$this->PCD_PromotionCode->contains($pCDPromotionCode)) {
            $this->PCD_PromotionCode[] = $pCDPromotionCode;
            $pCDPromotionCode->setCPPCD($this);
        }

        return $this;
    }

    public function removePCDPromotionCode(PromotionCode $pCDPromotionCode): self
    {
        if ($this->PCD_PromotionCode->removeElement($pCDPromotionCode)) {
            // set the owning side to null (unless already changed)
            if ($pCDPromotionCode->getCPPCD() === $this) {
                $pCDPromotionCode->setCPPCD(null);
            }
        }

        return $this;
    }

    public function getPCDNbrePromo(): ?int
    {
        return $this->PCD_NbrePromo;
    }

    public function setPCDNbrePromo(int $PCD_NbrePromo): self
    {
        $this->PCD_NbrePromo = $PCD_NbrePromo;

        return $this;
    }
}
