<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    public function findAllWithJoin($tag): array
    {

        $qb = $this->createQueryBuilder('p')
            ->addSelect('a', 't', 'c', 'l')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.createdAt', 'DESC');

        if ($tag !== null) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findWithJoin(int $id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->addSelect('a', 't', 'c', 'l', 'b')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('c.author', 'b')
            ->leftJoin('c.likes', 'l')
            ->leftJoin('p.tags', 't')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.author = :user')
            ->addSelect('c', 'l', 't')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->leftJoin('p.tags', 't')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
