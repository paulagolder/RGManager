<?php

namespace App\Repository;

use App\Entity\Seat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;


class SeatRepository  extends EntityRepository
{

    public function findone( $seatid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.SeatId = :stid");
       $qb->setParameter('stid', $seatid);
        $qb->orderBy("p.SeatId", "ASC");
       $seat =  $qb->getQuery()->getOneOrNullResult();
       return $seat;
    }

    public function findall()
    {
       $qb = $this->createQueryBuilder("p");
       $qb->orderBy("p.Name", "ASC");
       $seats =  $qb->getQuery()->getResult();
       return $seats;
    }

    public function findChildren($drid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.DistrictId = :drid");
       $qb->setParameter('drid', $drid);
       $qb->orderBy("p.Name", "ASC");
       $seats =  $qb->getQuery()->getResult();
       return $seats;
    }


     public function findLooseStreets($stid)
     {

     }

     public function findRoadgroups($dtid,$stid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select r.* from roadgroup as r where r.roadgroupid in (select DISTINCT roadgroupid from roadgrouptostreet as rs join street as s on rs.streetid = s.seq  and rs.year="'.$year.'"  where s.pdid in (SELECT pdid FROM `seattopd` sp WHERE sp.seat="'.$stid.'" and sp.district ="'.$dtid.'" and sp.year = "'.$year.'")) and r.year = "'.$year.'"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $rarray = array();
        foreach($roadgroups as $roadgroup)
        {
          $rarray[$roadgroup["roadgroupid"]]=$roadgroup;
        }
        return $rarray;
     }

     public function findSeatsforRoadgroup($rgid, $dtid, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
        // $sql = 'SELECT rg.* FROM seattopd as rg where rg.seatid = "' .$stid.'" and  rg.districtid ="'.$dtid.'";';
        // SELECT * FROM roadgroup WHERE `roadgroupid` IN (SELECT roadgroupid from street where pd = "RS")
        $sql = 'select s.* from seats as s where s.seatid in (SELECT seatid FROM `seattopd` sp WHERE sp.seat="'.$stid.'" and sp.district ="'.$dtid.'" and rs.year = "'.$year.'")';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $roadgroups;
     }

     public function xfindChildren($dtid)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select s.* from seats as s where s.districtid  ="'.$dtid.'" ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $seats= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $seats;
     }

     public function findSeats($level)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select s.* from seat as s where s.level  ="'.$level.'" ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
     //   $seats= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
           $seats= $stmt->fetchAll();
        return $seats;
     }

     public function findSeatsbyPD($stid)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select distinct s.seatid from seat s , seattopd spd where spd.seat = s.seatid and s.level="district" and   spd.pdid in (SELECT sp.pdid FROM `seattopd` as sp WHERE sp.seat = "'.$stid.'")';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        //   $seats= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $seats= $stmt->fetchAll();
        return $seats;
     }

     public function findPollingdistricts($dtid,$stid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select p.* from pollingdistrict as p where p.pdid in (select DISTINCT sp.pdid from seattopd as sp WHERE sp.seat="'.$stid.'" and sp.year ="'.$year.'") order by p.pdid';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $roadgroups;
     }


      public function countHouseholds($dtid,$stid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' Select SUM(r.households) as nos FROM `street` as r , seattopd as sp where r.pdid= sp.pdid and sp.seat="'.$stid.'" and sp.district ="'.$dtid.'" and sp.year ="'.$year.'" ';
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

      public function removepd($dtid,$stid,$pdid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'delete  from seattopd  WHERE seat="'.$stid.'" and district ="'.$dtid.'" and pdid="'.$pdid.'" and year ="'.$year.'"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
     }


       public function addpd($dtid,$stid,$pdid,$pdtag,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'insert into seattopd (district,seat, pdid,pdtag,year ) values ("'.$dtid.'", "'.$stid.'","'.$pdid.'","'.$pdtag.'","'.$year.'" );';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
     }

}
