<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

   /**
    * Method to find one book by its id
    * @param int $bookId
    * @return ?Book
    */
   public function findOneById(int $bookId): ?Book
   {
       return $this->createQueryBuilder('b')
           ->andWhere('b.id = :bookId')
           ->setParameter('bookId', $bookId)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
