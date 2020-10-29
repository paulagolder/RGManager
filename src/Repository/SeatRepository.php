<?php

namespace App\Repository;

use App\Entity\Seat;
use Doctrine\ORM\EntityRepository;



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

      public function findRoadgroups($dtid,$stid)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT rg.* FROM seattoroadgroup as rg where rg.seatid = "' .$stid.'" and  rg.districtid ="'.$dtid.'";';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }



}
