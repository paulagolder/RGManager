<?php

namespace App\Repository;

use App\Entity\Delivery;
use App\Entity\DeliverytoRoadgroup;
use Doctrine\ORM\EntityRepository;



class DeliveryRepository  extends EntityRepository
{

    public function findone($drid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DeliveryId = :drid");
       $qb->setParameter('drid', $drid);
       $qb->orderBy("p.DeliveryId", "ASC");
       $Delivery =  $qb->getQuery()->getOneOrNullResult();
       return $Delivery;
    }

    public function findall()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $Deliverys =  $qb->getQuery()->getResult();
       return $Deliverys;
    }

    public function findcurrent()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $Deliverys =  $qb->getQuery()->getResult();
       return $Deliverys;
    }


     public function findCandidateDeliveryRoadgroups($delivery,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$delivery->getDistrict().'" ';
        if($delivery->getSeat())
         $sqlpd .= ' and s.seat = "'.$delivery->getSeat().'"';
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


      public function findDeliveryRoadgroups($dyid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select r.* from roadgroup as r, deliverytoroadgroup as d where r.roadgroupid = d.roadgroupid and d.deliveryid = '.$dyid.' and r.year = "'.$year.'"';
        $stmt = $conn->executeQuery($sql);
        $roadgroups= $stmt->fetchAll();
        $rgsarray =[];
        foreach($roadgroups as $rg)
        {

        $rgsarray[$rg["roadgroupid"]] = $rg;
        }
        return $rgsarray;
     }

}
