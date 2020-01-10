<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */

    public function findTasks($user_id)
    {
        return $this->createQueryBuilder('t')
            ->where('t.todolist = :todolist_id')
            ->andWhere('t.deleted = 0')
            ->orderBy('t.ordre')
            ->setParameter('todolist_id', $user_id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLast() {
        $qb = $this->createQueryBuilder('tc');
        $qb->setMaxResults( 1 );
        $qb->orderBy('tc.id', 'DESC');

        return $qb->getQuery()->getSingleResult();
    }
    /*
    public function findOneBySomeField($value): ?Task
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
