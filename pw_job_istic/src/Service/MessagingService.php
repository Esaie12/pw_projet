<?php

namespace App\Service;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

class MessagingService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function sendMessage(
        string $senderType,
        int $senderId,
        string $receiverType,
        int $receiverId,
        string $content
    ): Message {
        $message = new Message();
        $message->setSenderType($senderType)
                ->setSenderId($senderId)
                ->setReceiverType($receiverType)
                ->setReceiverId($receiverId)
                ->setContent($content);

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }
}
