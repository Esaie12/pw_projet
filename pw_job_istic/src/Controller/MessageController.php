<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\UserRepository; 
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/dev/message', name: 'app_dev_message')]
    public function index(UserRepository $userRepository, MessageRepository $messageRepository): Response
    {
        $currentUser = $this->getUser();
        $users = $userRepository->findActiveUsersExcludingCurrent($currentUser->getId());
        
        // Récupérer les conversations avec les derniers messages
        $conversations = $messageRepository->findConversationsWithLastMessage($currentUser->getId());
        // Associer les utilisateurs pour chaque conversation
        $conversationData = [];
        foreach ($conversations as $conversation) {
           
            $message = $conversation;

            $partnerId = ($message->getSenderId() === $currentUser->getId()) ? $message->getReceiverId() : $message->getSenderId();
            $partner = $userRepository->find($partnerId);

            $conversationData[] = [
                'user' => $partner,
                'lastMessage' => $message,
            ];
        }
        
        return $this->render('developer/messages/index.html.twig', [
            'users' => $users,
            'conversations' => $conversationData,
            'active_tab'=> 'chat'
        ]);
    }

    #[Route('/dev/messages/{id}', name: 'app_message_user')]
    public function messageWithUser(  int $id,  UserRepository $userRepository, MessageRepository $messageRepository,  EntityManagerInterface $em,  Request $request ): Response {
        
        $currentUser = $this->getUser();
        $users = $userRepository->findActiveUsersExcludingCurrent($currentUser->getId());

        $targetUser = $userRepository->find($id);

        if (!$targetUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Récupérer les messages entre l'utilisateur connecté et l'utilisateur ciblé
        $messages = $messageRepository->findBy([
            'senderId' => [$currentUser->getId(), $targetUser->getId()],
            'receiverId' => [$targetUser->getId(), $currentUser->getId()],
        ], ['createdAt' => 'ASC']);

        // Gestion de l'envoi de message
        if ($request->isMethod('POST')) {
            $content = $request->request->get('message');
            if ($content) {
                $message = new Message();
                $message->setContent($content);
                $message->setCreatedAt(new \DateTime());
                $message->setSenderType(get_class($currentUser)); // Récupérer la classe (developer/society)
                $message->setSenderId($currentUser->getId());
                $message->setReceiverType(get_class($targetUser)); // Récupérer la classe (developer/society)
                $message->setReceiverId($targetUser->getId());
                $message->setIsRead(false);

                $em->persist($message);
                $em->flush();

                return $this->redirectToRoute('app_message_user', ['id' => $id]);
            }
        }

        return $this->render('developer/messages/conversation.html.twig', [
            'users' => $users,
            'messages' => $messages,
            'targetUser' => $targetUser,
            'active_tab'=> 'messagerie'
        ]);
    }



    #[Route('/society/message', name: 'app_society_message')]
    public function index_society(UserRepository $userRepository, MessageRepository $messageRepository): Response
    {
        $currentUser = $this->getUser();
        $users = $userRepository->findActiveUsersExcludingCurrent($currentUser->getId());
        
        // Récupérer les conversations avec les derniers messages
        $conversations = $messageRepository->findConversationsWithLastMessage($currentUser->getId());
        // Associer les utilisateurs pour chaque conversation
        $conversationData = [];
        foreach ($conversations as $conversation) {
           
            $message = $conversation;

            $partnerId = ($message->getSenderId() === $currentUser->getId()) ? $message->getReceiverId() : $message->getSenderId();
            $partner = $userRepository->find($partnerId);

            $conversationData[] = [
                'user' => $partner,
                'lastMessage' => $message,
            ];
        }
        
        return $this->render('society/messages/index.html.twig', [
            'users' => $users,
            'conversations' => $conversationData,
            'active_tab'=> 'messagerie'
        ]);
    }

    #[Route('/society/messages/{id}', name: 'app_society_user')]
    public function messageWithUser_society(  int $id,  UserRepository $userRepository, MessageRepository $messageRepository,  EntityManagerInterface $em,  Request $request ): Response {
        
        $currentUser = $this->getUser();
        $users = $userRepository->findActiveUsersExcludingCurrent($currentUser->getId());

        $targetUser = $userRepository->find($id);

        if (!$targetUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Récupérer les messages entre l'utilisateur connecté et l'utilisateur ciblé
        $messages = $messageRepository->findBy([
            'senderId' => [$currentUser->getId(), $targetUser->getId()],
            'receiverId' => [$targetUser->getId(), $currentUser->getId()],
        ], ['createdAt' => 'ASC']);

        // Gestion de l'envoi de message
        if ($request->isMethod('POST')) {
            $content = $request->request->get('message');
            if ($content) {
                $message = new Message();
                $message->setContent($content);
                $message->setCreatedAt(new \DateTime());
                $message->setSenderType(get_class($currentUser)); // Récupérer la classe (developer/society)
                $message->setSenderId($currentUser->getId());
                $message->setReceiverType(get_class($targetUser)); // Récupérer la classe (developer/society)
                $message->setReceiverId($targetUser->getId());
                $message->setIsRead(false);

                $em->persist($message);
                $em->flush();

                return $this->redirectToRoute('app_society_user', ['id' => $id]);
            }
        }

        return $this->render('society/messages/conversation.html.twig', [
            'users' => $users,
            'messages' => $messages,
            'targetUser' => $targetUser,
            'active_tab'=> 'messagerie'
        ]);
    }

}
