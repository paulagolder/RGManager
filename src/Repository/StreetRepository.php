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

    public function findOne($stname,$stpart)
    {
      $sn= $stname;
      $sp = $stpart;
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.Name = :sn');
       $qb->andWhere('p.Part = :sp  or p.Part is null  ');
       $qb->setParameter('sn', $sn);
        $qb->setParameter('sp', $sp);
       $street =  $qb->getQuery()->getOneOrNullResult();
       return $street;
    }

 public function findOnebyStreetId($streetid)
    {
      $sname = explode("/",$streetid);
      $sn = $sname[0];
      if(count($sname)== 2) $sp = $sname[1];
      else $sp = "";
       $qb = $this->createQueryBuilder("p");
       $qb->Where('p.Name = :sn');
       $qb->andWhere('p.Part = :sp  or p.Part is null  ');
       $qb->setParameter('sn', $sn);
        $qb->setParameter('sp', $sp);
       $street =  $qb->getQuery()->getOneOrNullResult();
       return $street;
    }

    public function findnamed($streetid,$year )
    {
     $sname = explode("/",$streetid);
      $sn = $sname[0];
      if(count($sname)== 2) $sp = $sname[1];
      else $sp = "";

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.*,rg.roadgroupid FROM street as s left join roadgrouptostreet as rg on s.name = rg.street where s.name = "'.$sn.'" and rg.year="'.$year.'" ;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
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

    public function xfindgroup($roadgroupid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->andWhere('p.RoadgroupId = :rgid ');
       $qb->setParameter('rgid', $roadgroupid);
       $qb->orderBy("p.Name", "ASC");
       $streets =  $qb->getQuery()->getResult();
       return $streets;
    }

     public function findLooseStreets($year)
     {

        $conn = $this->getEntityManager()->getConnection();
         $sql = 'select  st.*   from street as st  where concat (st.name,"-",st.part) not in ( SELECT concat (s.name,"-",s.part)  FROM street as s, roadgrouptostreet as rg WHERE s.name = rg.street and (s.part = rg.part or rg.part is null  or rg.part = "" ) and rg.year = "'.$year.'")  order by st.pd , st.name ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
      //  return null;
     }


 public function findGroup($roadgroupid, $year)
     {

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.* FROM `street` as s JOIN roadgrouptostreet as r on s.name = r.street and (s.part= r.part or s.part is null  or s.part="" ) WHERE r.roadgroupid = "'.$roadgroupid.'" and r.year = "'.$year.'" order by s.name ; ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $streets= $stmt->fetchAll();
        return $streets;
     }


}
