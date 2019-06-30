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
            ->select('(g.conference) AS confid, c.title , c.content,
             AVG(g.ref_note) AS moyen, COUNT(g.ref_note) AS nbrvote')
            ->leftJoin('g.conference', 'c')
            ->groupBy('g.conference')
            ->orderBy('moyen ', 'DESC')
            ->orderBy('nbrvote', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
/**
    public function getComment(int $idConf)
    {
        return $this->createQueryBuilder('g')
            ->select('g.content, g.ref_note, c.username, c.userfirstname')
            ->where('g.conference = :id')
            ->leftJoin('g.user_id', 'c')
            ->orderBy('g.publish_date', 'DESC')
            ->setParameter(':id', $idConf )
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
 **/

    /**
     * @param string $sCom
     * @param int $conf
     */
    public function modifyComment(string $sCom, int $conf)
    {
        $query = $this->createQueryBuilder('g')
            ->update($this->_entityName, 'g')
            ->set('g.content', '?1')
            ->where('g.id = ?2')
            ->setParameter(1, $sCom)
            ->setParameter(2, $conf)
            ->getQuery();

        $query->execute();
    }
    /**
     * @param string $sCom
     * @param int $conf
     */
    public function addComment(string $sCom, int $conf)
    {
        $query = $this->createQueryBuilder('g')
            ->update($this->_entityName, 'g')
            ->set('g.content', '?1')
            ->where('g.id = ?2')
            ->setParameter(1, $sCom)
            ->setParameter(2, $conf)
            ->getQuery();

        $query->execute();
    }

    public function getComments(int $conf)
    {
        return $this->createQueryBuilder('a')
            ->where('a.conference = ?2')
            ->andWhere('a.content != :val')
            ->setParameter(':val', '')
            ->setParameter(2, $conf)
            ->getQuery()
            ->getResult();
    }

    public function getAvg(int $conf)
    {
        return $this->createQueryBuilder('a')
            ->select('AVG(a.ref_note) AS moyen')
            ->where('a.conference != :val')
            ->setParameter(':val', $conf)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
