<?php

namespace App\Repository;

use App\Entity\SpaceObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpaceObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceObject[]    findAll()
 * @method SpaceObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceObject::class);
    }

    // /**
    //  * @return SpaceObject[] Returns an array of SpaceObject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpaceObject
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
