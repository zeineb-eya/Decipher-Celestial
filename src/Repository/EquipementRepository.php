<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;
use App\Entity\Equipement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;


/**
 * @method Equipement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipement[]    findAll()
 * @method Equipement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipement::class);
    } 
    
    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e
            FROM App:Equipement e
        WHERE e.nom_equipement LIKE :str or e.etat_equipement LIKE :str or e.description_equipement LIKE :str'

            )->setParameter('str', '%'.$str.'%')->getResult();
    }
}