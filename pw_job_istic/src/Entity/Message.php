<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    // L'émetteur peut être un développeur ou une société
    #[ORM\Column(type: 'string', length: 255)]
    private string $senderType; // 'developer' ou 'society'

    #[ORM\Column(type: 'integer')]
    private int $senderId;

    // Le destinataire peut être un développeur ou une société
    #[ORM\Column(type: 'string', length: 255)]
    private string $receiverType; // 'developer' ou 'society'

    #[ORM\Column(type: 'integer')]
    private int $receiverId;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isRead = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSenderType(): string
    {
        return $this->senderType;
    }

    public function setSenderType(string $senderType): self
    {
        $this->senderType = $senderType;

        return $this;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getReceiverType(): string
    {
        return $this->receiverType;
    }

    public function setReceiverType(string $receiverType): self
    {
        $this->receiverType = $receiverType;

        return $this;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): self
    {
        $this->receiverId = $receiverId;

        return $this;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
