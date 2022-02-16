<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
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
    private $answer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $admin_file;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reply_date;

    /**
     * @ORM\OneToOne(targetEntity=Reclamation::class, inversedBy="reponse", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reclamation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getAdminFile(): ?string
    {
        return $this->admin_file;
    }

    public function setAdminFile(?string $admin_file): self
    {
        $this->admin_file = $admin_file;

        return $this;
    }

    public function getReplyDate(): ?\DateTimeInterface
    {
        return $this->reply_date;
    }

    public function setReplyDate(\DateTimeInterface $reply_date): self
    {
        $this->reply_date = $reply_date;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }
}
