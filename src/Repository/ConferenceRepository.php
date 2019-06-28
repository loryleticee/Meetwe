<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findAll()
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    /**
     * @param int $currentPage
     * @return mixed
     * @throws \Exception
     */
    public function orderConference(int $currentPage = 1)
    {
         return $this->createQueryBuilder('a')
            ->orderBy('a.publish_date', 'DESC')
            ->andWhere('a.publish_date <= :val')
            ->setParameter('val', new \DateTime('now'))
            ->getQuery()
            ->setMaxResults(10)
            ->setFirstResult(($currentPage-1) * 10)
            ->getResult()
            ;
    }

    public function orderConferenceAdmin(int $currentPage = 1)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.publish_date', 'DESC')
            ->getQuery()
            ->setMaxResults(10)
            ->setFirstResult(($currentPage-1) * 10)
            ->getResult()
            ;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function nbrConference()
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.publish_date <= :val')
            ->setParameter('val', new \DateTime('now'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function nbrConferenceAdmin()
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @param string|null $sParam
     * @return mixed
     */
    public function search(?string $sParam)
    {
        return $this->createQueryBuilder('a')
        ->andWhere('a.title LIKE  :val')
        ->setParameter('val', '%'.$sParam.'%')
        ->orderBy('a.id', 'ASC')
        ->getQuery()
        ->getResult();
    }
    // /**
    //  * @return Conference[] Returns an array of Conference objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conference
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
