<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\PseudoTypes\False_;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private $E_Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $E_PHOTO;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $E_NOTE;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $E_Place;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $E_Price;

    /**
     * @ORM\Column(type="integer")
     */
    private $E_TelNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $E_Email;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEName(): ?string
    {
        return $this->E_Name;
    }

    public function setEName(string $E_Name): self
    {
        $this->E_Name = $E_Name;

        return $this;
    }

    public function getEPHOTO(): ?string
    {
        return $this->E_PHOTO;
    }

    public function setEPHOTO(string $E_PHOTO): self
    {
        $this->E_PHOTO = $E_PHOTO;

        return $this;
    }

    public function getENOTE(): ?string
    {
        return $this->E_NOTE;
    }

    public function setENOTE(?string $E_NOTE): self
    {
        $this->E_NOTE = $E_NOTE;

        return $this;
    }

    public function getEPlace(): ?string
    {
        return $this->E_Place;
    }

    public function setEPlace(string $E_Place): self
    {
        $this->E_Place = $E_Place;

        return $this;
    }

    public function getEPrice(): ?string
    {
        return $this->E_Price;
    }

    public function setEPrice(string $E_Price): self
    {
        $this->E_Price = $E_Price;

        return $this;
    }

    public function getETelNumber(): ?int
    {
        return $this->E_TelNumber;
    }

    public function setETelNumber(int $E_TelNumber): self
    {
        $this->E_TelNumber = $E_TelNumber;

        return $this;
    }

    public function getEEmail(): ?string
    {
        return $this->E_Email;
    }

    public function setEEmail(string $E_Email): self
    {
        $this->E_Email = $E_Email;

        return $this;
    }
}
