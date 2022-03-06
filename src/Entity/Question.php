<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse4;

    /**
     * @ORM\Column(type="integer")
     */
    private $repcorrect;

    /**
     * @return mixed
     */
    public function getRepcorrect()
    {
        return $this->repcorrect;
    }

    /**
     * @param mixed $repcorrect
     */
    public function setRepcorrect($repcorrect): void
    {
        $this->repcorrect = $repcorrect;
    }

    /**
     * @return mixed
     */
    public function getReponse2()
    {
        return $this->reponse2;
    }

    /**
     * @param mixed $reponse2
     */
    public function setReponse2($reponse2): void
    {
        $this->reponse2 = $reponse2;
    }

    /**
     * @return mixed
     */
    public function getReponse3()
    {
        return $this->reponse3;
    }

    /**
     * @param mixed $reponse3
     */
    public function setReponse3($reponse3): void
    {
        $this->reponse3 = $reponse3;
    }

    /**
     * @return mixed
     */
    public function getReponse4()
    {
        return $this->reponse4;
    }

    /**
     * @param mixed $reponse4
     */
    public function setReponse4($reponse4): void
    {
        $this->reponse4 = $reponse4;
    }

    /**
     * @return mixed
     */
    public function getReponse1()
    {
        return $this->reponse1;
    }

    /**
     * @param mixed $reponse1
     */
    public function setReponse1($reponse1): void
    {
        $this->reponse1 = $reponse1;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $id): self
    {
        $this->quiz = $id;

        return $this;
    }
}
