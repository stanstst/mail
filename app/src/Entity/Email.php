<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 * @ORM\Table(name="emails")
 * @ORM\HasLifecycleCallbacks()
 */
class Email
{
    use Timestamps;

    const STATUS_PENDING = 'pending';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fromEmail;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fromName;

    /**
     * @Assert\NotBlank
     *
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @todo add  Custom validator
     * @ORM\Column(type="array", length=255)
     */
    private $recipients = [];

    /**
     * @ORM\Column(type="text")
     */
    private $textPart;

    /**
     * @ORM\Column(type="text")
     */
    private $htmlPart;

    /**
     * @Assert\NotBlank
     *
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::STATUS_PENDING;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(string $fromName): self
    {
        $this->fromName = $fromName;

        return $this;
    }

    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    public function setFromEmail($from): void
    {
        $this->fromEmail = $from;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): void
    {
        $this->recipients = $recipients;
    }

    public function getTextPart()
    {
        return $this->textPart;
    }

    public function setTextPart($textPart): void
    {
        $this->textPart = $textPart;
    }

    public function getHtmlPart()
    {
        return $this->htmlPart;
    }

    public function setHtmlPart($htmlPart): void
    {
        $this->htmlPart = $htmlPart;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
