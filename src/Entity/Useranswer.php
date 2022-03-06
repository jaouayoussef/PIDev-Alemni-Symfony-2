<?php

namespace App\Entity;

use App\Repository\UseranswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UseranswerRepository::class)
 */
class Useranswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_user;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_question;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_quiz;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $rep_correct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdQuestion(): ?int
    {
        return $this->id_question;
    }

    public function setIdQuestion(int $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getIdQuiz(): ?int
    {
        return $this->id_quiz;
    }

    public function setIdQuiz(int $id_quiz): self
    {
        $this->id_quiz = $id_quiz;

        return $this;
    }

    public function getRepCorrect(): ?int
    {
        return $this->rep_correct;
    }

    public function setRepCorrect(int $rep_correct): self
    {
        $this->rep_correct = $rep_correct;

        return $this;
    }
}
