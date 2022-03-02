<?php

namespace App\Entity;

use App\Repository\ReservationEventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationEventRepository::class)
 */
class ReservationEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservationEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserId;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="reservationEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $EventId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PrixReservationEvent;


    /**
     * @ORM\Column(type="string", length=255)
     */


    public function getPrixReservationEvent(): ?string
    {
        return $this->PrixReservationEvent;
    }

    public function setPrixReservationEvent(string $PrixReservationEvent): self
    {
        $this->PrixReservationEvent = $PrixReservationEvent;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getEventId(): ?Event
    {
        return $this->EventId;
    }

    public function setEventId(?Event $EventId): self
    {
        $this->EventId = $EventId;

        return $this;
    }
}
