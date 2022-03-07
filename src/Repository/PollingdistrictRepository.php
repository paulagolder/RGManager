<?php

namespace App\Repository;

use App\Entity\Pollingdistrict;
use Doctrine\ORM\EntityRepository;



class PollingdistrictRepository  extends EntityRepository
{

     public function findAllbyYear($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select p.pollingdistrictid,p.households , p.electors,s.*  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" ';
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
          $pds[$pdid]["households"] = $pdseat["households"];
          $pds[$pdid]["electors"] = $pdseat["electors"];
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

     public function countHouseholds($pdid, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
       $sql = 'SELECT SUM(s.households) as nos  FROM `street` as s  WHERE  s.pd = "'.$pdid.'"  group by s.pdid ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $harray= $stmt->fetchAll();
      if(array_key_exists(0, $harray))
      {
      return $harray[0]["nos"];
      }
      else
        return 0;
     }


}
