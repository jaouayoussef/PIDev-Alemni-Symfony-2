<?php

namespace App\Entity;

use App\Repository\PromotionCodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionCodeRepository::class)
 */
class PromotionCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PromoCodeOwner", inversedBy="PCD_PromotionCode")
     */
    private $CP_PCD;

    /**
     * @return mixed
     */
    public function getCPPCD()
    {
        return $this->CP_PCD;
    }

    /**
     * @param mixed $CP_PCD
     */
    public function setCPPCD($CP_PCD): void
    {
        $this->CP_PCD = $CP_PCD;
    }


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PC_Code;

    /**
     * @ORM\Column(type="integer")
     */
    private $PC_Value;

    /**
     * @ORM\Column(type="date")
     */
    private $PC_ExpirationCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PC_Note;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPromoCodeOwner(): ?PromoCodeOwner
    {
        return $this->CP_PCD;
    }

    public function setPromoCodeOwner(?PromoCodeOwner $CP_PCD): self
    {
        $this->CP_PCD = $CP_PCD;

        return $this;
    }


    public function getPCCode(): ?string
    {
        return $this->PC_Code;
    }

    public function setPCCode(string $PC_Code): self
    {
        $this->PC_Code = $PC_Code;

        return $this;
    }

    public function getPCValue(): ?int
    {
        return $this->PC_Value;
    }

    public function setPCValue(int $PC_Value): self
    {
        $this->PC_Value = $PC_Value;

        return $this;
    }

    public function getPCExpirationCode(): ?\DateTimeInterface
    {
        return $this->PC_ExpirationCode;
    }

    public function setPCExpirationCode(\DateTimeInterface $PC_ExpirationCode): self
    {
        $this->PC_ExpirationCode = $PC_ExpirationCode;

        return $this;
    }

    public function getPCNote(): ?string
    {
        return $this->PC_Note;
    }

    public function setPCNote(?string $PC_Note): self
    {
        $this->PC_Note = $PC_Note;

        return $this;
    }
}
