<?php

namespace App\Repository;

use App\Entity\Round;
use App\Entity\RoundtoRoadgroup;
use Doctrine\ORM\EntityRepository;



class RoundRepository  extends EntityRepository
{

    public function findOne($rnid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoundId = :rnid");
       $qb->setParameter('rnid', $rnid);
       $Round =  $qb->getQuery()->getOneOrNullResult();
       return $Round;
    }

      public function findRounds($dvyid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :dvid");
       $qb->setParameter('dvid', $dvyid);
       $qb->orderBy("p.RoundId", "ASC");
       $Rounds =  $qb->getQuery()->getResult();
       return $Rounds;
    }

    public function findall()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $Rounds =  $qb->getQuery()->getResult();
       return $Rounds;
    }

    public function findcurrent()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $Rounds =  $qb->getQuery()->getResult();
       return $Rounds;
    }


     public function findCandidateRoundRoadgroups($round,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$round->getDistrict().'" ';
        if($round->getSeat())
         $sqlpd .= ' and s.seat = "'.$round->getSeat().'"';
        //dump($sqlpd);
        $stmt = $conn->prepare($sqlpd);
        $stmt->execute();
        $pollingdistricts = $stmt->fetchAll();
       // dump($pollingdistricts);
        $pdlist = "";
        foreach($pollingdistricts  as $key => $value)
        {
          $pdlist  .= '"'.$value["pollingdistrictid"].'", ';
        }
        $pdlist .= '"xx"';
      //  dump($pdlist);
        $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs  WHERE rs.pd IN('.$pdlist.')   and rs.year = "'.$year.'") and r.year = "'.$year.'"';
        $stmt = $conn->executeQuery($sql);
        $roadgroups= $stmt->fetchAll();
        $rglist=[];
        foreach($roadgroups as $rg)
        {
           $rglist[$rg["roadgroupid"]]= $rg;
        }
        return $rglist;
     }


      public function getRoadgroups($rnid)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select rg.*  from roundtoroadgroup as r , roadgroup rg  where r.roundid ='.$rnid.' and r.roadgroupid = rg.roadgroupid ';
      //  $em->createQuery
      //$users = $query->getResult();
        $stmt = $conn->executeQuery($sql);
        $roadgroups= $stmt->fetchAll();
        $rgsarray = array();
        foreach($roadgroups as $roadgroup)
        {
           $rgsarray[$roadgroup["roadgroupid"]] = $roadgroup;
        }
        return $rgsarray;
     }

}
