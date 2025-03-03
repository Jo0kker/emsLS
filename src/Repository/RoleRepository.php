<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function findLowRole()
    {
        return $this->getEntityName()
            ->createQuery('r')
            ->where('r.rang = 0')
            ->getQuery()
            ->getResult();
    }

    public function findPublicRole()
    {
        return $this->createQueryBuilder('r')
            ->where('r.title != :employ')
            ->andWhere('r.title != :civil')
            ->setParameter('employ', 'Employe')
            ->setParameter('civil', 'Civil')
            ->orderBy('r.rang', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPatron()
    {
        return $this->createQueryBuilder('r')
            ->where('r.menuAccess = 1')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Role[] Returns an array of Role objects
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
    public function findOneBySomeField($value): ?Role
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
