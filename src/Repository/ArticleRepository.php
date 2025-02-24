<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArticlesBySearch($search): array
    {

        return $this->createQueryBuilder('a')
            ->andWhere('a.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('a.id', 'ASC')
            //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findSortByOrder($sortField, $sortOrder): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy($sortField, $sortOrder)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
