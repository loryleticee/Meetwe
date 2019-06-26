<?php

namespace App\Repository;

use App\Entity\RefNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RefNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefNote[]    findAll()
 * @method RefNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefNoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RefNote::class);
    }

    // /**
    //  * @return RefNote[] Returns an array of RefNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefNote
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
