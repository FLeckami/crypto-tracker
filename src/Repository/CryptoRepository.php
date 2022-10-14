<?php

namespace App\Repository;

use App\Entity\Crypto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Crypto>
 *
 * @method Crypto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crypto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crypto[]    findAll()
 * @method Crypto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crypto::class);
    }

    public function save(Crypto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Crypto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Crypto[] Returns an array of Crypto objects
    */
   public function sumOfCrypto($crypto_name)
   {
       return $this->createQueryBuilder('c')
           ->select('SUM(c.crypto_qty)')
           ->where('c.crypto_name = :name')
           ->setParameter('name', $crypto_name)
           ->getQuery()
           ->getSingleResult()
       ;
   }

//    public function findOneBySomeField($value): ?Crypto
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
