<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

class ConversationController extends AbstractController
{
    private $userRepository;
    private $entityManager;
    private $conversationRepository;

    public function __construct(
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager,
        ConversationRepository $conversationRepository
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
    }

    #[Route('/conversation', name: 'app_new_conversation', methods:['POST'])]
    public function newConversation(Request $request): JsonResponse
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new \Exception("Usuario no encontrado.");
        }
        
        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new \Exception("No puedes crear una conversación contigo mismo.");
        }

        //Verificar si la conversación ya existe
        $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId(),
        );
        // dd($conversation); //Da el resultado de esta linea en especifico
        if (count($conversation)) {
            throw new \Exception("La conversación ya existe.");
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUserId($this->getUser());
        $participant->setConversationId($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUserId($otherUser);
        $otherParticipant->setConversationId($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try { 
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);
            
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return $this->json([
            'id' => $conversation->getId(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/conversation', name: 'app_get_conversation', methods:['GET'])]
    public function getConversations(Request $request): JsonResponse
    {
        $conversations = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());
        
        $hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure', $hubUrl));
        // dd($conversations);
        return $this->json($conversations);
    }
}

