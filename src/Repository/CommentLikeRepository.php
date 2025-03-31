<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\CommentLike;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CommentLike>
 */
class CommentLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentLike::class);
    }

    public function findLikedCommentsByUser(User $user): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.comment', 'c')
            ->where('l.user = :user')
            ->orderBy('l.createdAt', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?CommentLike
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
