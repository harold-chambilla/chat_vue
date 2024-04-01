<?php

namespace App\Security\Voter;

use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter
{
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository) {
        $this->conversationRepository = $conversationRepository;
    }
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    // const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // dd($attribute, $subject);
        return $attribute == self::VIEW && $subject instanceof Conversation;
        // return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $result = $this->conversationRepository->checkIfUserisParticipant(
            $subject->getId(),
            $token->getUser()->getId()
        );
        // dd($result);
        return !!$result;
    }

    private function canView(): bool
    {
        return true;
    }

    private function canEdit(): bool
    {
        return true;
    }
}