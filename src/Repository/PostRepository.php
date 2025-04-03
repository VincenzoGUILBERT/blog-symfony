<?php

namespace App\Repository;

use App\Entity\Post;
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
    public function findAllWithJoin($param, $tag): array
    {

        $qb = $this->createQueryBuilder('p')
            ->addSelect('a', 't', 'c', 'COUNT(l.id) as totalLikes')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->leftJoin('p.tags', 't')
            ->groupBy('a.id', 't.id', 'p.id', 'c.id')
            ->orderBy('p.createdAt', 'DESC');

        switch ($param) {
            case 'populars':
                $qb->orderBy('totalLikes', 'DESC');
                break;
            default:
                break;
        }

        if ($tag !== null) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }


        return $qb->getQuery()
            ->getResult();
    }

    public function findWithJoin($id): ?Post
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
