<?php

namespace App\Repository;

use App\Entity\PDFEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PDFEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PDFEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PDFEntity[]    findAll()
 * @method PDFEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PDFEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PDFEntity::class);
    }

//    /**
//     * @return PDFEntity[] Returns an array of PDFEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PDFEntity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
