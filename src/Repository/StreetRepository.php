<?php

namespace App\Repository;

use App\Entity\Street;
use Doctrine\ORM\EntityRepository;



class StreetRepository  extends EntityRepository
{

    public function findAll()
    {

       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

      public function findProblems($filter)
    {

      $qb = $this->createQueryBuilder("p");
      if ($filter == "rg")
       {
         $qb->Where("p.RoadgroupId IS NULL "); // NOT EMPTY
        }
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

    public function findOne($streetid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.StreetId = :pid');
       $qb->setParameter('pid', $streetid);
       $street =  $qb->getQuery()->getOneOrNullResult();
       return $street;
    }

     public function findSearch($sfield)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.surname LIKE :pid or p.forename LIKE :pid   or p.alias LIKE :pid   ');
       $qb->setParameter('pid', $sfield);
       $qb->orderBy("p.surname", "ASC");
       $people =  $qb->getQuery()->getResult();
       foreach( $people as $person)
       {
          $person->fixperson();
       }
       return $people;
    }

         public function findnamed($streetname)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.Name = :stnm ');
       $qb->setParameter('stnm', $streetname);
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

       public function findgroup($roadgroupid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.RoadgroupId = :rgid ');
       $qb->setParameter('rgid', $roadgroupid);
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
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

       public function findNotInRoadgroupList($swdid, $roadgrouplist)
     {
         $qb = $this->createQueryBuilder("p");
         $qb->Where("p.SubwardId = :swdid  ");
         $qb->andWhere("p.RoadgroupId NOT IN ( :rgl ) "); // NOT EMPTY
         $qb->setParameter('rgl', $roadgouplist);
         $qb->setParameter('swdid', $subwardid);
         $qb->orderBy("p.Name", "ASC");
         $streets =  $qb->getQuery()->getResult();
       return $streets;

     }


}
