<?php

namespace App\Repository;

use App\Entity\Parti;
use App\Entity\Politicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Parti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parti[]    findAll()
 * @method Parti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parti::class);
    }

    public function findPartiPoliticiens($politicien){
         $qb = $this->createQueryBuilder('p')
            ->join('p.politiciens','s')
            ->Join('s.parti','a')
            ->where('s.parti IN (:politicien)')
             ->setParameter('politicien', $politicien);
         return $qb->getQuery()->getResult();
    }


    public function findMoyenneAge($id){
        return $this->createQueryBuilder('p')
            ->join('p.politiciens','po')
            ->select('avg(po.age)')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->groupBy('p.nom')
            ->getQuery()
            ->getResult();
    }

    public function findNombreFemme($id){
    return $this->createQueryBuilder('p')
        ->join('p.politiciens','po')
        ->select('count(po.sexe)')
        ->andWhere('p.id = :id')
        ->andWhere('po.sexe = :sexe')
        ->setParameter('sexe', 'F')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();
    }

    public function findNombreHomme($id){
        return $this->createQueryBuilder('p')
            ->join('p.politiciens','po')
            ->select('count(po.sexe)')
            ->andWhere('p.id = :id')
            ->andWhere('po.sexe = :sexe')
            ->setParameter('sexe', 'M')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


}
