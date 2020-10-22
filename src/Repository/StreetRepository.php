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

    public function findDuplicates()
    {
        $qb = $this->createQueryBuilder("s");
        $qb->select(['s.Name', 's.Part', 'count(s) as Count']);
       // $qb->from('App:Street', 's');
         $qb->groupBy('s.Name');
        $qb->addGroupBy('s.Part');
         $qb->Having('count(s)  > 1');

       $qb->orderBy("count(s)", "DESC");
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



    public function findnamed($streetname)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.Name = :stnm ');
       $qb->setParameter('stnm', $streetname);
       $qb->orderBy("p.PD", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

    public function namesearch($streetname)
    {
       $streettoken = "%".$streetname."%";
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.Name like :stnm ');
       $qb->setParameter('stnm', $streettoken);
       $qb->orderBy("p.PD", "ASC");
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

     public function findLooseStreets()
     {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT s.streetid, s.name, s.part, s.PD FROM street as s left join roadgroup as r on s.roadgroupid = r.roadgroupid left join ward as w on r.wardid = w.wardid where w.wardid is null';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
     }





}
