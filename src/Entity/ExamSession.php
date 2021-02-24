<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ExamSessionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ExamSessionRepository::class)
 */
class ExamSession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exam", inversedBy="questions", cascade={"persist"})
     */
    private Exam $exam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="students", cascade={"persist"})
     */
    private Question $question;

    /**
     * @ORM\Column(type="string", length=250)
     * @Assert\NotBlank
     */
    private string $answer;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     */
    private bool $isValid;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Exam
     */
    public function getExam(): Exam
    {
        return $this->exam;
    }

    /**
     * @param Exam $exam
     */
    public function setExam(Exam $exam): void
    {
        $this->exam = $exam;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }
}
