<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quiz", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $questions;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\OneToOne(targetEntity=Formation::class, inversedBy="quiz")
     */
    private $id_formation;

    /**
     * @ORM\OneToMany(targetEntity=Userresult::class, mappedBy="quiz")
     */
    private $userresults;

    public function __construct()
    {
        $this->userresults = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions): void
    {
        $this->questions = $questions;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdFormation(): ?Formation
    {
        return $this->id_formation;
    }

    public function setIdFormation(?Formation $id_formation): self
    {
        $this->id_formation = $id_formation;

        return $this;
    }

    /**
     * @return Collection|Userresult[]
     */
    public function getUserresults(): Collection
    {
        return $this->userresults;
    }

    public function addUserresult(Userresult $userresult): self
    {
        if (!$this->userresults->contains($userresult)) {
            $this->userresults[] = $userresult;
            $userresult->setQuiz($this);
        }

        return $this;
    }

    public function removeUserresult(Userresult $userresult): self
    {
        if ($this->userresults->removeElement($userresult)) {
            // set the owning side to null (unless already changed)
            if ($userresult->getQuiz() === $this) {
                $userresult->setQuiz(null);
            }
        }

        return $this;
    }
}
