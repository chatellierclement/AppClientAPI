<?php

namespace App\Repository;

use App\Entity\RendezvousEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RendezvousEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezvousEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezvousEntity[]    findAll()
 * @method RendezvousEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezvousEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RendezvousEntity::class);
    }

     public function countRendezVousByDate($date)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb
             ->select('COUNT(a)')
             ->andWhere(
                    $qb->expr()->like('a.dateDebut', ':dateDebut'))
             ->setParameter('dateDebut', $date.'%')
             ->getQuery()
             ->getSingleScalarResult();
    }

    public function getRendezVousByDate($date)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb
             ->select('a')
             ->andWhere(
                    $qb->expr()->like('a.dateDebut', ':dateDebut'))
             ->setParameter('dateDebut', $date.'%')
             ->addOrderBy('a.dateDebut', 'ASC')
             ->getQuery()
             ->getResult();
    }
}
