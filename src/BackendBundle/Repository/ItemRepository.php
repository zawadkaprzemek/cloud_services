<?php

namespace App\BackendBundle\Repository;

use App\BackendBundle\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getProducts(string $amountFilter="all")
    {
        $qb=$this->createQueryBuilder('i');
        switch($amountFilter){
            case 'more_than_5':
                $qb
                    ->andWhere('i.amount > :amount')
                    ->setParameter('amount',5)
                ;
                break;
            case 'out_of_stock':
                $qb
                    ->andWhere('i.amount = :amount')
                    ->setParameter('amount',0)
                ;
                break;
            default:
                break;
        }
        return $qb
            ->getQuery()
            ->getResult()
            ;


    }
}
