<?php

namespace App\Repository;

use App\Entity\Rggroup;
use Doctrine\ORM\EntityRepository;



class RggroupRepository  extends EntityRepository
{

     public function xfindAll()
     {
        $conn = $this->getEntityManager()->getConnection();
       // $sql = 'select w.*, sum(rg.households) as total from rggroup as w left join roadgroup as rg on rg.rggroupid= w.rggroupid group by w.rggroupid';
        $sql = 'select rg.* from rggroup as rg';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $groups= $stmt->fetchAll();
        return $groups;
     }

    public function findAll()
    {
       $qb = $this->createQueryBuilder("p");
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

    public function findone($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rggroupid = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->orderBy("p.Rggroupid", "ASC");
       $group =  $qb->getQuery()->getOneOrNullResult();
       return $group;
    }

     public function countHouseholds($wdid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' Select SUM(r.households) as nos FROM roadgroup as rg , roadgrouptostreet as rs , street as r  where rg.roadgroupid= rs.roadgroupid and rs.street = r.name  and rs.part = r.part and rg.rggroupid="'.$wdid.'" and rs.year ="'.$year.'" and rg.year ="'.$year.'" ';
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $harray= $stmt->fetchAll();
      if(array_key_exists(0, $harray ))
      {

      return $harray[0]["nos"];
      }
      else
        return 0;
     }



}
