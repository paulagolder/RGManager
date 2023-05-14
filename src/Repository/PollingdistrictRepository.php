<?php

namespace App\Repository;

use App\Entity\Pollingdistrict;
use Doctrine\ORM\EntityRepository;



class PollingdistrictRepository  extends EntityRepository
{

     public function findAllbyYear($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select p.pdid,p.districtid as dpd,p.households , p.electors,s.*,st.*  from pollingdistrict  as p left join seattopd as s on  s.pdid =  p.pdid left join  seat as st on s.seat = st.seatid  order by p.districtid, p.pdid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pdseats= $stmt->fetchAll();
        $pds =[];
        dump($pdseats);
        foreach($pdseats as $pdseat)
        {

          $pdid = $pdseat["pdid"];
          if(!array_key_exists($pdid, $pds))
          {
             $pds[$pdid] = array();
          }
          $district = $pdseat["dpd"];
          $seat = $pdseat["seat"];
          $pds[$pdid]["districtid"] =  $pdseat["dpd"];;
          $pds[$pdid][ $pdseat["level"]] = $seat;
          $pds[$pdid]["households"] = $pdseat["households"];
          $pds[$pdid]["electors"] = $pdseat["electors"];
        }
        return $pds;
     }


    public function findone($pdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.PdId = :apdid");
       $qb->setParameter('apdid',$pdid);
       $apd =  $qb->getQuery()->getOneOrNullResult();
       return $apd;
    }


     public function findSpares($dtid,  $year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select  q.pdid , q.districtid from pollingdistrict as q  where q.pdid not in  (select  p.pdid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pdid and s.district = "'.$dtid.'" and year = "'.$year.'")  order by q.pdid';
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

     public function findSeat($pdid,  $year, $level)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select  sp.seat from  seattopd as sp , seat as s where sp.seat=s.seatid and sp.pdid =  "'.$pdid.'"  and sp.year = "'.$year.'" and s.level = "'.$level.'"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pds= $stmt->fetchAll();
        dump($pds);
        if(count($pds)<1)  return null;
        return $pds[0]["seat"];
     }

}
