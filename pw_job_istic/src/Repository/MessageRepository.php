<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findLastMessageByUser(int $userId): ?Message
    {
        return $this->createQueryBuilder('m')
            ->where('m.senderId = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findConversationBetweenUsers(int $userId1, int $userId2): array
    {
        return $this->createQueryBuilder('m')
            ->where('(m.senderId = :user1 AND m.receiverId = :user2) OR (m.senderId = :user2 AND m.receiverId = :user1)')
            ->setParameter('user1', $userId1)
            ->setParameter('user2', $userId2)
            //->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findConversationsWithLastMessage(int $userId): array
    {
        $subQuery = $this->createQueryBuilder('m')
            ->select('IDENTITY(m.senderId) AS senderId', 'MAX(m.createdAt) AS lastMessageDate')
            ->where('m.receiverId = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('m.senderId')
            ->getDQL();

        return $this->getEntityManager()->createQuery("
            SELECT m
            FROM App\Entity\Message m
            WHERE m.senderId = m.senderId 
                AND m.receiverId = :userId
                OR  m.senderId = :userId
            ORDER BY m.createdAt DESC
        ")->setParameter('userId', $userId)
        ->getResult();
    }


//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
