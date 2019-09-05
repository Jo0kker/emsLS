<?php

namespace App\Repository;

use App\Entity\MenuAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MenuAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuAccess[]    findAll()
 * @method MenuAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuAccessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MenuAccess::class);
    }

    // /**
    //  * @return MenuAccess[] Returns an array of MenuAccess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuAccess
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
