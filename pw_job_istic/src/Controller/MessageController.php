<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }


    #[Route('/message/send', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request, MessagingService $messagingService)
    {
        $senderType = $request->get('sender_type'); // 'developer' ou 'society'
        $senderId = $request->get('sender_id'); // ID de l'expéditeur
        $receiverType = $request->get('receiver_type'); // 'developer' ou 'society'
        $receiverId = $request->get('receiver_id'); // ID du destinataire
        $content = $request->get('content');

        if (!$senderType || !$receiverType || !$content) {
            return $this->json(['error' => 'Données invalides'], 400);
        }

        $message = $messagingService->sendMessage($senderType, $senderId, $receiverType, $receiverId, $content);

        return $this->json(['message' => 'Message envoyé', 'id' => $message->getId()]);
    }


    #[Route('/messages/{userType}/{userId}', name: 'get_messages', methods: ['GET'])]
    public function getMessages(string $userType, int $userId)
    {
        $messages = $this->getDoctrine()->getRepository(Message::class)->findBy([
            'receiverType' => $userType,
            'receiverId' => $userId
        ]);

        return $this->json($messages);
    }

}
