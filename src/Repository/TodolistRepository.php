<?php

namespace App\Repository;

use App\Entity\Todolist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Todolist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todolist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todolist[]    findAll()
 * @method Todolist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodolistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todolist::class);
    }
    public function findLists($user_id){
        return $this->createQueryBuilder('t')
            ->where('t.user = :user_id')
            ->andWhere('t.deleted = 0')
            ->orderBy('t.id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findCurrentList($list_id,$user_id){
        return $this->createQueryBuilder('t')
            ->where('t.id = :list_id')
            ->andWhere('t.user=:user_id')
            ->andWhere('t.deleted = 0')
            ->setParameters(['list_id'=>$list_id,
                             'user_id'=>$user_id])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    // /**
    //  * @return Todolist[] Returns an array of Todolist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Todolist
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
