<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Conversation $conversation_id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $user_id = null;

    #[ORM\OneToMany(mappedBy: 'last_message_id', targetEntity: Conversation::class)]
    private Collection $conversations;

    #[ORM\Column]
    private ?bool $mine = null;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
        $this->created_at = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

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

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): static
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setLastMessageId($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getLastMessageId() === $this) {
                $conversation->setLastMessageId(null);
            }
        }

        return $this;
    }

    public function isMine(): ?bool
    {
        return $this->mine;
    }

    public function setMine(bool $mine): static
    {
        $this->mine = $mine;

        return $this;
    }
}
