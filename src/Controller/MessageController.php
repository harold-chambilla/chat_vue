<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    private $entityManager;
    private $messageRepository;
    private $userRepository;
    private $participantRepository;
    private $hubInterface;

    public function __construct(
        EntityManagerInterface $entityManager, 
        MessageRepository $messageRepository,
        UserRepository $userRepository,
        ParticipantRepository $participantRepository,
        HubInterface $hubInterface,
        ) {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->participantRepository = $participantRepository;
        $this->hubInterface = $hubInterface;
    }
    #[Route('/message/{id}', name: 'app_get_message', methods: ['GET'])]
    public function index(Request $request, Conversation $conversation): Response
    {
        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $this->messageRepository->findMessageByConversationId(
            $conversation->getId(),
        );
        array_map(function ($message) {
            $message->setMine(
                $message->getUserId()->getId() === $this->getUser()->getId() ? true: false
            );
        }, $messages);  
        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    #[Route('/message/{id}', name: 'app_new_message', methods: ['POST'])]
    public function newMessage(Request $request, Conversation $conversation, SerializerInterface $serializer): Response
    {
        // $data = json_decode($request->getContent(), true);

        // $user = $this->userRepository->findOneBy(['id' => 2]);
        $user = $this->getUser();

        $recipient = $this->participantRepository->findParticipantByConversationIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );
        
        $user = $this->getUser();
        $content = $request->request->get('content', null);
        if ($content === null) {
            // Manejar el caso en el que 'content' es nulo
            // Puedes lanzar una excepción o devolver un error apropiado
            return new JsonResponse(['error' => 'Contenido unlo'], 400);
        }
        $message = new Message();
        $message->setContent($content);
        $message->setUserId($user);
        $message->setMine(false);

        $conversation->addMessage($message);
        $conversation->setLastMessageId($message);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        
        // $message->setMine(false);
        // $messageSerialized = $serializer->serialize($message, 'json', [
        //     'attributes' => ['id', 'content', 'createdAt', 'mine', 'conversation' => ['id']]
        // ]);
        // $update = new Update(
        //     [
        //         sprintf("/conversation/%s", $conversation->getId()),
        //         sprintf("/conversation/%s", $recipient->getUserId()->getEmail())
        //     ],
        //     $messageSerialized, 
        //     sprintf("/%s", $recipient->getUserId()->getEmail())   
        // );
        // $this->hubInterface->publish($update);
        // $message->setMine(true);
        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}
