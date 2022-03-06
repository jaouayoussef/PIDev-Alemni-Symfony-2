<?php

namespace App\Entity;

use App\Repository\PromotionCodeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromotionCodeRepository::class)
 */
class PromotionCode
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
    private $PC_Code;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     */
    private $PC_Value;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     */
    private $PC_ExpirationCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PC_Note;

    /**
     * @ORM\ManyToOne(targetEntity=PromoCodeOwner::class, inversedBy="PCD_PromotionCode")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(
     *     message = "Cette valeur ne doit pas être vide"
     * )
     */
    private $CP_PCD;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getCPPCD(): ?PromoCodeOwner
    {
        return $this->CP_PCD;
    }

    public function setCPPCD(?PromoCodeOwner $CP_PCD): self
    {
        $this->CP_PCD = $CP_PCD;

        return $this;
    }


}
