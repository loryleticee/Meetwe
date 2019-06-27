<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }
    public function getConference()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.publish_date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getTopTen()
    {
        return $this->createQueryBuilder('g')
            ->select('(g.conference) AS confid,AVG(g.ref_note) AS moyen, COUNT(g.ref_note) AS nbrvote')
            ->groupBy('g.conference')
            ->orderBy('moyen', 'DESC', 'nbrvote', 'DESC')
            ->setMaxResults('10')
            ->getQuery()
            ->getResult();

    }
}
