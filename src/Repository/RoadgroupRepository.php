<?php

namespace App\Repository;

use App\Entity\Roadgroup;
use Doctrine\ORM\EntityRepository;


class RoadgroupRepository extends EntityRepository
{

     public function findOne($rgid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoadgroupId = :rgid");
       $qb->setParameter('rgid', $rgid);
       $roadgroup =  $qb->getQuery()->getOneOrNullResult();;
       return $roadgroup;
    }

    public function findChildren($swdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rgsubgroupid = :swdid");
       $qb->setParameter('swdid', $swdid);
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

     public function findLooseRoadgroups()
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT DISTINCT r.roadgroupid,r.name  FROM roadgroup as r left join rggroup as w on r.rggroupid = w.rggroupid where w.rggroupid is null or r.rgsubgroupid is null';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }

     public function findAllCurrent($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT r.* , count(*) as nos FROM `roadgroup` as r left join roadgrouptostreet as rs on r.roadgroupid = rs.roadgroupid where rs.year = "'.$year.'" group by r.roadgroupid ORDER BY r.roadgroupid ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }


    public function findRoadgroupsinRGGroup($wdid)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rggroupid = :wdid");
       $qb->setParameter('wdid', $wdid);
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

     public function findPDs()
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT  r.roadgroupid  as rg,s.pd as pd  FROM roadgroup as r left join street as s on r.roadgroupid = s.roadgroupid  order by pd , rg ' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }

     public function findAllinPollingDistrict($pdid, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs  WHERE rs.pd = "'.$pdid.'"  and rs.year = "'.$year.'")';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }


     public function addStreet($astreet,$roadgroupid,$year)
     {
       $street= $astreet->getName();
       $part= $astreet->getPart();
       $pd = $astreet->getPD();
       $conn = $this->getEntityManager()->getConnection();
       $sql = "insert into  roadgrouptostreet (street,part,pd,roadgroupid,year)  VALUES ( \"$street\", \"$part\", \"$pd\", \"$roadgroupid\", \"$year\" ) ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
     }

}
