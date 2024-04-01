<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $messages_read_at = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?Conversation $conversation_id = null;

    public function __construct() {
        $this->messages_read_at = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessagesReadAt(): ?\DateTimeInterface
    {
        return $this->messages_read_at;
    }

    public function setMessagesReadAt(\DateTimeInterface $messages_read_at): static
    {
        $this->messages_read_at = $messages_read_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getConversationId(): ?Conversation
    {
        return $this->conversation_id;
    }

    public function setConversationId(?Conversation $conversation_id): static
    {
        $this->conversation_id = $conversation_id;

        return $this;
    }
}
