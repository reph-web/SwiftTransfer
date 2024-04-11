<?php

namespace App\Repository;

use App\Entity\ContactListMembership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactListMembership>
 *
 * @method ContactListMembership|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactListMembership|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactListMembership[]    findAll()
 * @method ContactListMembership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactListMembershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactListMembership::class);
    }

//    /**
//     * @return ContactListMembership[] Returns an array of ContactListMembership objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContactListMembership
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
