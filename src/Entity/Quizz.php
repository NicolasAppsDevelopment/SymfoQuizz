<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'quizz', cascade: ["persist", 'remove'])]
    private Collection $questions;

    #[ORM\OneToMany(targetEntity: UserQuizzAttempt::class, mappedBy: 'quizz', cascade: ["persist", 'remove'])]
    private Collection $usersAttempts;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->usersAttempts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {

        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuizz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }

        return $this;
    }

    public function getUsersAttempts(): Collection
    {
        return $this->usersAttempts;
    }

    public function addUserAttempt(UserQuizzAttempt $userAttempt): self
    {

        if (!$this->usersAttempts->contains($userAttempt)) {
            $this->usersAttempts->add($userAttempt);
            $userAttempt->setQuizz($this);
        }

        return $this;
    }

    public function removeUserAttempt(UserQuizzAttempt $userAttempt): self
    {
        if ($this->usersAttempts->removeElement($userAttempt)) {
            if ($userAttempt->getQuizz() === $this) {
                $userAttempt->setQuizz(null);
            }
        }

        return $this;
    }

}