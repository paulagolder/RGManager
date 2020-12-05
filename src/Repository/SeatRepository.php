<?php

namespace App\Repository;

use App\Entity\Seat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;


class SeatRepository  extends EntityRepository
{

    public function findone($districtid, $seatid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.SeatId = :stid");
       $qb->setParameter('stid', $seatid);
        $qb->andwhere("p.DistrictId = :dtid");
       $qb->setParameter('dtid', $districtid);
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
        // $sql = 'SELECT rg.* FROM seattopd as rg where rg.seatid = "' .$stid.'" and  rg.districtid ="'.$dtid.'";';
        // SELECT * FROM roadgroup WHERE `roadgroupid` IN (SELECT roadgroupid from street where pd = "RS")
        $sql = 'select r.* from roadgroup as r where roadgroupid in (select DISTINCT roadgroupid from roadgrouptostreet as rs join street as s on (rs.street = s.name and ( rs.part = s.part or ( rs.part is null or rs.part = "" ))) where s.pd in (SELECT pdid FROM `seattopd` sp WHERE sp.seat="'.$stid.'" and sp.district ="'.$dtid.'" and rs.year = "'.$year.'"))';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $roadgroups;
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

       public function findPollingdistricts($dtid,$stid,$year)
     {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select p.* from pollingdistrict as p where p.pollingdistrictid in (select DISTINCT sp.pdid from seattopd as sp WHERE sp.seat="'.$stid.'" and sp.district ="'.$dtid.'" and sp.year = (select max(spi.year)  from seattopd as spi where spi.seat="'.$stid.'" and spi.district ="'.$dtid.'"))';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $roadgroups;
     }


}
