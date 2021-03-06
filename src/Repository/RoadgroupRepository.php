<?php

namespace App\Repository;

use App\Entity\Roadgroup;
use Doctrine\ORM\EntityRepository;


class RoadgroupRepository extends EntityRepository
{

    public function findOne($rgid,$year)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.RoadgroupId = :rgid");
       $qb->andwhere("p.Year = :yr");
       $qb->setParameter('rgid', $rgid);
       $qb->setParameter('yr', $year);
       $roadgroup =  $qb->getQuery()->getOneOrNullResult();;
       return $roadgroup;
    }

    public function findAllbyYear($year)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Year = :yr");
       $qb->setParameter('yr', $year);
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

    public function findChildren($swdid, $year)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rgsubgroupid = :swdid");
       $qb->setParameter('swdid', $swdid);
       $qb->andwhere("p.Year = :yr");
       $qb->setParameter('yr', $year);
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


    public function findRoadgroupsinRGGroup($wdid, $year)
    {
       $qb = $this->createQueryBuilder("p");
       $qb->where("p.Rggroupid = :wdid");
       $qb->setParameter('wdid', $wdid);
       $qb->andwhere("p.Year = :yr");
       $qb->setParameter('yr', $year);
       $roadgroups =  $qb->getQuery()->getResult();
       return $roadgroups;
    }

     public function findPDs()
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT  r.roadgroupid  as rg,s.pd as pd  FROM roadgroup as r left join street as s on r.roadgroupid = s.roadgroupid and r.year=s.year  order by pd , rg ' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }

     public function findAllinPollingDistrict($pdid, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs  WHERE rs.pd = "'.$pdid.'"  and rs.year = "'.$year.'") and r.year = "'.$year.'"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }


     public function findDeliveryRoadgroups($delivery,$year)
     {
        $conn = $this->getEntityManager()->getConnection();
            $sqlpd = 'select p.pollingdistrictid  from pollingdistrict  as p, seattopd as s where s.pdid =  p.pollingdistrictid and s.year = "'.$year.'" and s.district = "'.$delivery->district.'" ';
        $stmt = $conn->prepare($sqlpd);

        $pollingdistricts = $stmt->fetchAll();

        dump($pollingdistricts);
        $pdarry =
          $sql = 'select r.* from roadgroup as r where r.roadgroupid in (SELECT rs.roadgroupid FROM `roadgrouptostreet` as rs  WHERE rs.pd IN(:pdlist)   and rs.year = "'.$year.'") and r.year = "'.$year.'"';

          $stmt = $conn->executeQuery(
        $sql,
        [
            'pdlist' => $pdarray
        ],
        [
            'pdlist' => \Doctrine\DBAL\Connection::PARAM_STR_ARRAY
        ]
    );

        $roadgroups= $stmt->fetchAll();



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


     public function updateCounts($year)
     {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT r.roadgroupid, count(*) as nos FROM `roadgroup` as r left join roadgrouptostreet as rs on r.roadgroupid = rs.roadgroupid where rs.year = "'.$year.'" group by r.roadgroupid ORDER BY r.roadgroupid ';
         $sql2 = 'SELECT r.roadgroupid, r.year, count(*) as nos FROM `roadgroup` as r left join roadgrouptostreet as rs on r.roadgroupid = rs.roadgroupid where rs.year = "'.$year.'" group by r.roadgroupid ORDER BY r.roadgroupid ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $roadgroups= $stmt->fetchAll();
        return $roadgroups;
     }

     public function countHouseholds($roadgroupid, $year)
     {
        $conn = $this->getEntityManager()->getConnection();
       $sql = 'SELECT SUM(s.households) as nos  FROM `street` as s JOIN roadgrouptostreet as rs on s.name = rs.street and (s.part= rs.part or s.part is null  or s.part="" ) WHERE rs.year = "'.$year.'" and rs.roadgroupid = "'.$roadgroupid.'"  group by rs.roadgroupid ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $harray= $stmt->fetchAll();
        if(array_key_exists(0, $harray))
        {
           return $harray[0]["nos"];
        }
        else
          return -999;
     }
}
