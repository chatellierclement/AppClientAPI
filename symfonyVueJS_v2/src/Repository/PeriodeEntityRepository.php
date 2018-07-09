<?php

namespace App\Repository;

use App\Entity\PeriodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PeriodeEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeriodeEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeriodeEntity[]    findAll()
 * @method PeriodeEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodeEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PeriodeEntity::class);
    }

   
}
