<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function triaauthor()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.email', 'Asc')
            ->getQuery()
            ->getResult();

    }
    public function searchNbBook(?int $min , $max)
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('   SELECT a
                                                 FROM App\Entity\Author a
                                                 WHERE a.nbbooks BETWEEN :min AND :max
    ');
    $query->setParameter('min', $min);
    $query->setParameter('max', $max);
    return $query->getResult();
    }


    public function deleteZero()
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('  DELETE FROM App\Entity\Author a
                                     WHERE a.nbbooks = :NBbooks
    ');
    $query->setParameter('NBbooks',0);
    $query->execute();

    }
   

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
