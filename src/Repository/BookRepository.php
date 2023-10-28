<?php

namespace App\Repository;
use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchbook($ref) {
        return $this->createQueryBuilder('b')
        ->where('b.ref = :ref')
        ->setParameter('ref', $ref)
        ->getQuery()
        ->getResult();

    }
// fonction tri les book selon les username d'author
    public function triabookauthor()
    {
        return $this->createQueryBuilder('b')
            ->Join('b.author', 'a')
            ->orderBy('a.username', 'ASC')
            ->getQuery()
            ->getResult();

    }

// fonction les book avant année 2023 d'un auteur nb_books=35
    public function bookparannee()
    {
        return $this->createQueryBuilder('b')
            ->Join('b.author', 'a')
            ->where('b.pubplicationDate<:date')
            ->andWhere('a.nbbooks>:count')
            ->setParameter('date', new \DateTime('2023-01-01'))
            ->setParameter('count', 35)
            ->getQuery()
            ->getResult();

    }

//mise à jours la colonne category
    public function updatecategory()
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('  UPDATE App\Entity\Book b
                                     SET b.category = :newCategory
                                     WHERE b.author IN (
                                                         SELECT a.id
                                                         FROM App\Entity\Author a
                                                         WHERE a.username = :authorUsername
    )');
    $query->setParameter('newCategory', 'Romance');
    $query->setParameter('authorUsername', 'William Shakespear');
    $query->execute();
    }

//fonction somme science fection 
    public function sommeScience()
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('   SELECT SUM(a.nbbooks)
                                                 FROM App\Entity\Author a
                                                 JOIN a.books b
                                                 WHERE b.category = :newCategory
    ');
    $query->setParameter('newCategory', 'Science Fiction');
    return $query->getSingleScalarResult();
    }

//fonction les livres entre 2014 et 2018
    public function bookIn()
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('   SELECT b, a
                                                 FROM App\Entity\Book b
                                                 JOIN b.author a
                                                 WHERE b.pubplicationDate BETWEEN :date1 AND :date2
    ');
    $query->setParameter('date1', new \DateTime('2014-01-01'));
    $query->setParameter('date2', new \DateTime('2018-12-31'));
    return $query->getResult();
    }

// fonction nombre livre published
    public function nbPublished()
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('   SELECT COUNT(b.published)
                                      FROM App\Entity\Book b
                                      WHERE b.published = :nbpublished
                                                 
    ');
       $query->setParameter('nbpublished', 1);
    return $query->getSingleScalarResult();
 
    }
// fonction nombre livre unpublished
    public function nbUnPublished()
    { 
        $em = $this->getEntityManager();
        $query = $em->createQuery('   SELECT COUNT(b.published)
                                      FROM App\Entity\Book b
                                      WHERE b.published = :nbunpublished      
    ');
    $query->setParameter('nbunpublished', 0);
    return $query->getSingleScalarResult();
    
    }

    

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
