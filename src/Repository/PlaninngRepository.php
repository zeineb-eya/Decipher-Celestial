<?php

namespace App\Repository;

use App\Entity\Planinng;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Planinng|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planinng|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planinng[]    findAll()
 * @method Planinng[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaninngRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planinng::class);
    }

    // /**
      //* @return Planinng[] Returns an array of Planinng objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Planinng
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function orderByDatePlan()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.dateDebut_planning', 'DESC')

           // ->setMaxResults(3)
            ->getQuery()->getResult();
    }

    public function orderByPeriodePlan()
    {
        return $this->createQueryBuilder('pr')
            ->orderBy('pr.periode_planning', 'ASC')

           // ->setMaxResults(3)
            ->getQuery()->getResult();
    }
    public function orderByPrixPlan()
    {
        return $this->createQueryBuilder('per')
            ->orderBy('per.prix_planning', 'ASC')

           // ->setMaxResults(3)
            ->getQuery()->getResult();
    }

public function search($nom_planning) {
    return $this->createQueryBuilder('Planinng')
        ->andWhere('Planinng.nom_planning LIKE :nom_planning')
        ->setParameter('title', '%'.$nom_planning.'%')
        ->getQuery()
        ->execute();
}
}
