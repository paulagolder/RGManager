<?php

namespace App\Repository;

use App\Entity\Pollingdistrict;
use Doctrine\ORM\EntityRepository;



class PollingdistrictRepository  extends EntityRepository
{

     public function findAll()
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select p.pollingdistrictid, s.*  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pdseats= $stmt->fetchAll();

        $pds =[];
        foreach($pdseats as $pdseat)
        {

          $pdid = $pdseat["pdid"];
          if(!array_key_exists($pdid, $pds))
          {
             $pds[$pdid] = array();
          }
          $district = $pdseat["district"];
          $seat = $pdseat["seat"];
          $pds[$pdid][$district] = $seat;
        }
        return $pds;
     }


    public function findone($pdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.PollingDistrictId = :pdid");
       $qb->setParameter('pdid', $pdid);
       $qb->orderBy("p.PollingDistrictId", "ASC");
       $apd =  $qb->getQuery()->getOneOrNullResult();
       return $apd;
    }


     public function findSpares($dtid,  $year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select  q.pollingdistrictid  from pollingdistrict as q  where q.pollingdistrictid not in  (select  p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.district = "'.$dtid.'" and year = "'.$year.'")';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pds= $stmt->fetchAll();
        return $pds;
     }


}
