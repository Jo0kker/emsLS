<?php

namespace App\Repository;

use App\Entity\Mutuelle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Mutuelle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mutuelle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mutuelle[]    findAll()
 * @method Mutuelle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MutuelleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mutuelle::class);
    }

    public function findBuyMutuelle()
    {
        return $this->createQueryBuilder('m')
            ->where('m.prix > 0')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Mutuelle[] Returns an array of Mutuelle objects
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
    public function findOneBySomeField($value): ?Mutuelle
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
