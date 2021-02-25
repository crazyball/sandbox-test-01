<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class ExamSessionAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", cascade={"persist"})
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ExamSession", inversedBy="answers")
     */
    private ExamSession $examSession;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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

    /**
     * @return ExamSession
     */
    public function getExamSession(): ExamSession
    {
        return $this->examSession;
    }

    /**
     * @param ExamSession $examSession
     */
    public function setExamSession(ExamSession $examSession): void
    {
        $this->examSession = $examSession;
    }
}
