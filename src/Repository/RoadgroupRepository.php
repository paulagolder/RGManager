<?php

namespace App\Repository;

use App\Entity\Roadgroup;
use Doctrine\ORM\EntityRepository;


class RoadgroupRepository extends EntityRepository
{

     public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.RoadgroupId", "ASC");
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }


     public function findOne($rgid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoadgroupId = :rgid");
       $qb->setParameter('rgid', $rgid);
       $roadgroup =  $qb->getQuery()->getOneOrNullResult();;
       return $roadgroup;
    }

    public function findChildren($swdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.SubwardId = :swdid");
       $qb->setParameter('swdid', $swdid);
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

     public function findNotInWardList($wardlist)
     {
         $qb = $this->createQueryBuilder("p");
         $qb->Where("p.WardId NOT IN ( :wl ) "); // NOT EMPTY
         $qb->setParameter('wl', $wardlist);
         $qb->orderBy("p.Name", "ASC");
         $streets =  $qb->getQuery()->getResult();
       return $streets;

     }

     public function findNotInSubList($wardid, $subwardlist)
     {
         $qb = $this->createQueryBuilder("p");
         $qb->Where("p.WardId = :wdid  ");
         $qb->andWhere("p.SubwardId NOT IN ( :swl ) "); // NOT EMPTY
         $qb->setParameter('swl', $subwardlist);
         $qb->setParameter('wdid', $wardid);
         $qb->orderBy("p.Name", "ASC");
         $streets =  $qb->getQuery()->getResult();
       return $streets;

     }
}
