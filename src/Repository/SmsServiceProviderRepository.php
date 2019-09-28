<?php

namespace App\Repository;

use App\Entity\SmsServiceProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SmsServiceProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmsServiceProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmsServiceProvider[]    findAll()
 * @method SmsServiceProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmsServiceProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmsServiceProvider::class);
    }

    // /**
    //  * @return SmsServiceProvider[] Returns an array of SmsServiceProvider objects
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
    public function findOneBySomeField($value): ?SmsServiceProvider
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
