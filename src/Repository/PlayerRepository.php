<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function getPlayersFromClub(int $limit, int $offset, int $clubId)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.currentClub = :clubId')
            ->setParameter('clubId', $clubId)
            ->getQuery()->setFirstResult($offset)
            ->setMaxResults($limit);
        $paginator = new Paginator($qb);
        return ['total' => count($paginator), 'items' => $paginator];
    }
}
